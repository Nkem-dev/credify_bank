<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VirtualCard;
use App\Models\VirtualCardTransaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VirtualCardController extends Controller
{
    /**
     * Display list of all virtual cards
     */
    public function index(Request $request)
    {
        // Statistics
        $totalCards = VirtualCard::count(); //total cards
        $activeCards = VirtualCard::where('status', 'active')->count();//total active cards
        $blockedCards = VirtualCard::where('status', 'blocked')->count();//total blocked cards
        $totalBalance = VirtualCard::sum('balance'); //the balance of the card

        // Build query
        $query = VirtualCard::with('user'); //eager loading using eloquent ORM t get the user with the user's virtual card

        // Filter by status:blocked, active 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by user or card number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('card_number', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by newest
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $cards = $query->paginate(20); //20 per page

        // return view with virtual card details
        return view('admin.virtual-cards.index', compact(
            'cards',
            'totalCards',
            'activeCards',
            'blockedCards',
            'totalBalance'
        ));
    }

    /**
     * Show virtual card details
     */
    public function show(VirtualCard $virtualCard)
    {
        $virtualCard->load('user'); //loads the card with the user: user relationship

        // return view with the virtual card
        return view('admin.virtual-cards.show', compact('virtualCard'));
    }

    /**
     * Block a virtual card
     */
    public function block(Request $request, VirtualCard $virtualCard)
    {
        // Only active cards can be blocked
        if ($virtualCard->status !== 'active') {
            return back()->with('error', 'Only active cards can be blocked.');
        }

        // validate the input
        $request->validate([
            'transaction_pin' => 'required|digits:4',
            'reason' => 'required|string|max:500',
        ]);

        $admin = auth()->user(); //get the currently logged in user that is the admin

        // Verify admin transaction pin
        if (!Hash::check($request->transaction_pin, $admin->transaction_pin)) {
            return back()->with('error', 'Incorrect transaction PIN.');
        }

        try {
            // update the status of the virtual card
            $virtualCard->update([
                'status' => 'blocked',
            ]);

            // log to channel
            Log::channel('admin_actions')->info('Admin blocked virtual card', [
                'admin_id' => $admin->id,
                'card_id' => $virtualCard->id,
                'user_id' => $virtualCard->user_id,
                'reason' => $request->reason,
            ]);

            // return with success message 
            return back()->with('success', 'Virtual card has been blocked successfully.');

        } catch (Exception $e) { //if it fails
            Log::error('Card block failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to block card. Please try again.');
        }
    }

    /**
     * Unblock a virtual card
     */
    public function unblock(Request $request, VirtualCard $virtualCard)
    {
        // Only blocked cards can be unblocked
        if ($virtualCard->status !== 'blocked') {
            return back()->with('error', 'Only blocked cards can be unblocked.');
        }

        // validate the input
        $request->validate([
            'transaction_pin' => 'required|digits:4',
        ]);

        $admin = auth()->user(); //get currently logged in user that is an admin

        // Verify admin transaction pin
        if (!Hash::check($request->transaction_pin, $admin->transaction_pin)) {
            return back()->with('error', 'Incorrect transaction PIN.');
        }

        try {
            // update the virtual card status
            $virtualCard->update([
                'status' => 'active',
            ]);

            // log channel
            Log::channel('admin_actions')->info('Admin unblocked virtual card', [
                'admin_id' => $admin->id,
                'card_id' => $virtualCard->id,
                'user_id' => $virtualCard->user_id,
            ]);

            // return with success message
            return back()->with('success', 'Virtual card has been unblocked successfully.');

        } catch (Exception $e) { //if it fails
            Log::error('Card unblock failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to unblock card. Please try again.');
        }
    }

    /**
     * Delete a virtual card
     */
    public function destroy(Request $request, VirtualCard $virtualCard)
    {
        // validate the input
        $request->validate([
            'transaction_pin' => 'required|digits:4',
            'reason' => 'required|string|max:500',
        ]);

        $admin = auth()->user(); //currently logged in user that is an admin

        // Verify admin transaction pin
        if (!Hash::check($request->transaction_pin, $admin->transaction_pin)) {
            return back()->with('error', 'Incorrect transaction PIN.');
        }

        // begin task
        DB::beginTransaction();

        try {
            $user = $virtualCard->user; //user's virtual card
            $cardBalance = $virtualCard->balance; //virtual card balanx\ce

            // If card has balance, refund to user
            if ($cardBalance > 0) { 
                $user->increment('balance', $cardBalance);
            }

            // Log the deletion
            Log::channel('admin_actions')->info('Admin deleted virtual card', [
                'admin_id' => $admin->id,
                'card_id' => $virtualCard->id,
                'user_id' => $virtualCard->user_id,
                'card_balance' => $cardBalance,
                'refunded' => $cardBalance > 0,
                'reason' => $request->reason,
            ]);

            // Delete the card
            $virtualCard->delete();

            // commit to database
            DB::commit(); 

            // return and redirect with success message
            return redirect()->route('admin.virtual-cards.index')
                ->with('success', 'Virtual card deleted successfully.' . 
                    ($cardBalance > 0 ? " ₦" . number_format($cardBalance, 2) . " refunded to user." : ''));

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Card deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete card. Please try again.');
        }
    }

    /**
     * Download cards report
     */
    public function downloadReport(Request $request)
    {
        // Build query with eager loading
        $query = VirtualCard::with('user');

        // Apply filters; filter by status 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // let the order start from newest created cards
        $cards = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'virtual_cards_report_' . date('Y-m-d_His') . '.csv';
        
        // header that tells the browser to download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Stream CSV Using a Callback (Memory Efficient)
        $callback = function() use ($cards) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Card ID',
                'Reference',
                'User Name',
                'User Email',
                'Card Number',
                'Balance',
                'Status',
                'Expiry',
                'Created Date',
            ]);

            // Data rows
            foreach ($cards as $card) {
                fputcsv($file, [
                    $card->id,
                    $card->reference,
                    $card->user->name ?? 'N/A',
                    $card->user->email ?? 'N/A',
                    $card->card_number,
                    $card->balance,
                    ucfirst($card->status),
                    $card->expiry_month . '/' . $card->expiry_year,
                    $card->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file); //close stream
        };

        // Laravel executes the callback and streams the CSV directly to the user
        return response()->stream($callback, 200, $headers);
    }
}