<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * Show account settings page
     */
    public function index()
    {
        $user = Auth::user(); //get current logged in users
        
        // show recent transactions
        $recentTransfers = $user->transfers()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
            // return view with recent transfers and user details
        return view('user.account.index', compact('user', 'recentTransfers'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); //currently logged in user

        // validate the inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
        ]);

        try {
            $user->update($validated); //if validation passes, update the user

            // return with sucess message
            return back()->with('success', 'Profile updated successfully!');
        } catch (Exception $e) { //if it fails
            Log::error('Profile update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user(); //currently logged in user

        // validate the inputs
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Verify current password to check if it matches the user's password in database 
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Check if new password is same as current password
        if (Hash::check($validated['new_password'], $user->password)) {
            return back()->with('error', 'New password must be different from current password.');
        }

        try {
            // if validaton is a sucess, update user new password
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            // return with success message
            return back()->with('success', 'Password changed successfully!');
        } catch (Exception $e) { //if it fails
            Log::error('Password update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * Update security settings (Change transaction PIN and 2FA)
     */
    public function updateSecurity(Request $request)
    {
        $user = Auth::user(); //currently logged in user

        // validate the inputs
        $validated = $request->validate([
            'current_pin' => 'nullable|required_with:new_transaction_pin|digits:4',
            'new_transaction_pin' => 'nullable|required_with:current_pin|digits:4|confirmed',
            'two_factor_enabled' => 'nullable|boolean',
        ], [
            'current_pin.required_with' => 'Current PIN is required to set new PIN.',
            'new_transaction_pin.required_with' => 'New PIN is required.',
            'new_transaction_pin.confirmed' => 'PIN confirmation does not match.',
        ]);

        try {
            DB::beginTransaction(); //begin task

            // Change transaction PIN 
            if ($request->filled('new_transaction_pin')) {
                // Verify current PIN and check if current pin matches what is in the databse
                if (!Hash::check($validated['current_pin'], $user->transaction_pin)) {
                    return back()->with('error', 'Current transaction PIN is incorrect.');
                }

                // Check if new PIN is same as current pin
                if (Hash::check($validated['new_transaction_pin'], $user->transaction_pin)) {
                    return back()->with('error', 'New PIN must be different from current PIN.');
                }

                // if it is a success, update user's new transaction pin
                $user->update([
                    'transaction_pin' => Hash::make($validated['new_transaction_pin']),
                ]);
            }

            // Update 2FA setting
            if ($request->has('two_factor_enabled')) {
                $user->update([
                    'two_factor_enabled' => $validated['two_factor_enabled'],
                ]);
            }

            DB::commit(); //commit to database

            // return with success message
            return back()->with('success', 'Security settings updated successfully!');
        } catch (Exception $e) { //if it fails
            DB::rollBack();
            Log::error('Security update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update security settings. Please try again.');
        }
    }
}