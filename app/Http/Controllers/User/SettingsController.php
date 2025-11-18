<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    // show settings view
    public function index()
    {
        $user = Auth::user(); //get authenticated user
        $settings = $user->getSettings(); //get the user's settings from  helper method
        
        // return view and get user and the user's settings
        return view('user.settings.index', compact('user', 'settings'));
    }

    // show account security view
     public function security()
    {
        $user = Auth::user(); //get the authenticated user
        // return security view and compact with user
        return view('user.settings.security', compact('user')); 
    }

    // update password
public function updatePassword(Request $request)
{
    $user = Auth::user(); //get authenticated user

    // validate input
    $request->validate([
        'current_password' => ['required', 'string'],
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ], [
        'current_password.required' => 'Please enter your current password.',
        'new_password.required' => 'Please enter a new password.',
        'new_password.min' => 'Password must be at least 8 characters.',
        'new_password.confirmed' => 'Password confirmation does not match.',
    ]);

    // Check if current password matches the user's password in the database
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'Current password is incorrect.');
    }

    // Check if new password is same as old password
    if (Hash::check($request->new_password, $user->password)) {
        return back()->with('error', 'New password must be different from current password.');
    }

    // Update password
    $user->password = Hash::make($request->new_password);
    $user->save();

    // Log password change
    Log::info('Password changed', [
        'user_id' => $user->id,
        'ip' => $request->ip(),
    ]);

    return back()->with('success', 'Password updated successfully!');
}

// update transaction pin
 public function updateTransactionPin(Request $request)
    {
        $user = Auth::user(); //get authenticated user

        // validate input
        $request->validate([
            'current_pin' => ['required', 'digits:4'],
            'new_pin' => ['required', 'digits:4', 'confirmed'],
        ], [
            'current_pin.required' => 'Please enter your current PIN.',
            'current_pin.digits' => 'PIN must be exactly 4 digits.',
            'new_pin.required' => 'Please enter a new PIN.',
            'new_pin.digits' => 'PIN must be exactly 4 digits.',
            'new_pin.confirmed' => 'PIN confirmation does not match.',
        ]);

        // Check if user has a transaction PIN set
        if (!$user->transaction_pin) {
            return back()->with('error', 'You do not have a transaction PIN set. Please set one first.');
        }

        // Verify current PIN
        if (!Hash::check($request->current_pin, $user->transaction_pin)) {
            return back()->with('error', 'Current PIN is incorrect.');
        }

        // Check if new PIN is same as old PIN
        if (Hash::check($request->new_pin, $user->transaction_pin)) {
            return back()->with('error', 'New PIN must be different from current PIN.');
        }

        

        // Update transaction PIN
        $user->transaction_pin = Hash::make($request->new_pin);
        $user->save();

        // Log PIN change
        Log::info('Transaction PIN changed', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
        ]);

        return back()->with('success', 'Transaction PIN updated successfully!');
    }

    // show preference view
    public function preferences()
{
    $user = Auth::user(); //get aythenticated user
    $settings = $user->getSettings(); //user's settings
    
    // languages
    $languages = [
        'en' => ['name' => 'English', 'flag' => '🇬🇧'],
        'yo' => ['name' => 'Yoruba', 'flag' => '🇳🇬'],
        'ig' => ['name' => 'Igbo', 'flag' => '🇳🇬'],
        'ha' => ['name' => 'Hausa', 'flag' => '🇳🇬'],
    ];
    
    // themes
    $themes = [
        'light' => ['name' => 'Light', 'icon' => 'ti-sun'],
        'dark' => ['name' => 'Dark', 'icon' => 'ti-moon'],
        'system' => ['name' => 'System', 'icon' => 'ti-device-desktop'],
    ];
    
    // return view and compact with user, settings, languages and themes
    return view('user.settings.preferences', compact('user', 'settings', 'languages', 'themes'));
}

public function updatePreferences(Request $request)
{
    $user = Auth::user();
    $settings = $user->getSettings();

    $validated = $request->validate([
        'language' => 'required|in:en,yo,ig,ha',
        'theme' => 'required|in:light,dark,system',
        'currency_format' => 'required|in:NGN,USD,EUR,GBP',
    ], [
        'language.required' => 'Please select a language.',
        'language.in' => 'Invalid language selection.',
        'theme.required' => 'Please select a theme.',
        'theme.in' => 'Invalid theme selection.',
        'currency_format.required' => 'Please select a currency format.',
    ]);

    $settings->update($validated);

    Log::info('Preferences updated', [
        'user_id' => $user->id,
        'language' => $validated['language'],
        'theme' => $validated['theme'],
    ]);

    return back()->with('success', 'Preferences updated successfully!');
}


}
