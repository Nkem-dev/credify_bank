<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\VirtualCard;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VirtualCardController extends Controller
{

    public function index()
{
    // checks if the authemticated user has a virtual card
    $card = auth()->user()->virtualCard;

    // if the user has a virtual card, make sure the card has a  reference number
    if ($card) {
        // Make sure reference exists before redirecting
        if (!$card->reference) {
            $card->reference = 'VC-' . strtoupper(Str::random(12));
            $card->save();
        }
        //redirects to show thw card
        return redirect()->route('user.cards.show', $card->reference);
    }
    
    // if the user does not have a card, show the create card form
    return view('user.virtualcard.createvirtualcard');
}

// displays create card form
    public function create()
    {
        // checks if the authenticated user already has a virtual card. if the card exists redirect to show the virtual card 
        if (auth()->user()->virtualCard) {
            return redirect()->route('user.cards.show', auth()->user()->virtualCard)
                ->with('info', 'You already have a card.');
        }


        // if no card. return this view to create virtual card
        return view('user.virtualcard.createvirtualcard');
    }

   
// submit form to create new card
    public function store(Request $request)
{
    // validate the pin input
    $request->validate(['pin' => 'required|digits:4']);

    // get the authenticated user
    $user = auth()->user();

    // check if  the pin the user input matches the user's transaction pin in the database and if it is not return with error message
    if (!Hash::check($request->pin, $user->transaction_pin)) {
        return back()->with('error', 'Incorrect PIN.');
    }

    // if the user already has a virtual card and is trying to create a new one, return with message
    if ($user->virtualCard) {
        return back()->with('error', 'You already have a card.');
    }

    // begin task
    DB::beginTransaction();

    try {
        // call the helper function in the virtual card model and store the details in $cardDetails variable to create new virtual visa card
        $cardDetails = VirtualCard::generateVisaCard();

        // remove spaces and dashes and replace with empty string '' (removes all non-numeric characters) in the card number
        $cleanCardNumber = preg_replace('/\D/', '', $cardDetails['card_number']);

        // creates new record in the virtual card table 
        $card = VirtualCard::create([
            'user_id' => $user->id,
            'reference' => 'VC-' . strtoupper(Str::random(12)),
            'card_number' => $cleanCardNumber, // ← Cleaned
            'expiry_month' => $cardDetails['expiry_month'],
            'expiry_year' => $cardDetails['expiry_year'],
            'cvv' => $cardDetails['cvv'],
            'balance' => $user->balance,
            'status' => 'active',
            'expires_at' => now()->addYears(3),
            'description' => 'My Visa Card',
        ]);

        // commit to database
        DB::commit();

        // return redirect to show the view of the card
        return redirect()->route('user.cards.show', $card)
            ->with('success', 'Visa card created!');
    } catch (Exception $e) {
        DB::rollBack(); //if it fails, rollback changes

        // log error message
        Log::error('Virtual Card Creation Failed: ' . $e->getMessage());

        return back()->with('error', app()->environment('local')
            ? 'Error: ' . $e->getMessage()
            : 'Failed. Try again.'
        );
    }
}

    //display card details
   public function show(VirtualCard $card)
{
    // Prevent users from accessing other users' cards
    if ($card->user_id !== auth()->id()) {
        abort(403);
    }

    // Ensure card has a valid reference code
    if (!$card->reference) {
        Log::error('Card missing reference', ['card_id' => $card->id]);
        return back()->with('error', 'Card reference missing. Please contact support.');
    }

    // show card view after doing necessary checks
    return view('user.virtualcard.show', compact('card'));
}


// delete virtual card
    public function destroy(VirtualCard $card)
    {
        // if the authenticated user's id does not match the card id, do not authorise to delete but if it matches
        if ($card->user_id !== auth()->id()) abort(403);

        // if there is a match, delete the card
        $card->delete();

        // return redirect to the card index view with success message
        return redirect()->route('user.cards.index')
            ->with('success', 'Card deleted succesfully.');
    }

    // verify transaction pin before reviewing card details
    public function verifyPin(Request $request)
{
    try {
        // validate pin input
        $request->validate([
            'pin' => 'required|digits:4'
        ]);

        // get the authenticated user
        $user = auth()->user();

        // Check if user has a transaction PIN set
        if (!$user->transaction_pin) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction PIN not set. Please set up your PIN first.'
            ], 400);
        }

        // Verify the PIN matches
        if (!Hash::check($request->pin, $user->transaction_pin)) {
            Log::warning('Failed PIN attempt for card access', [
                'user_id' => $user->id,
                'ip' => $request->ip()
            ]);

            // if the pin does not match
            return response()->json([
                'success' => false,
                'message' => 'Incorrect transaction PIN'
            ], 400);
        }

        Log::info('Card access granted', [
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PIN verified successfully'
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Please enter a valid 4-digit PIN'
        ], 422);
    } catch (Exception $e) {
        Log::error('PIN verification error', [
            'error' => $e->getMessage(),
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred. Please try again.'
        ], 500);
    }
}
   
}
