<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransactionPinController extends Controller
{
    public function show()
    {
        $user = Auth::user(); //gets the authenticated user

        // if the user has set transaction pin, redirect the user to login page
           /** @var \App\Models\User $user */ 
        if ($user->hasSetPin()) { 
            return redirect()->route('login');
        }

        // return the transaction pin view
        return view('auth.set-pin');
    }

   
    public function store(Request $request)
    {
        // var notation removes error because laravel's  $user = Auth::user();  method returns Illuminate\Contracts\Auth\Authenticatable|null by definition and the method does not guarantee methods like save(), hasSetPin(), or properties like transaction_pin.

        /** @var \App\Models\User $user */  
        $user = Auth::user();   //returns the currently logged in  user

        //if the user already has a pin send them to login
        if ($user->hasSetPin()) { 
            return redirect()->route('login');
        }

        // validate the inputs
       $request->validate([
    'pin' => ['required', 'digits:4', 'confirmed'],
    'pin_confirmation' => 'required',
     ], [
    'pin.required' => 'Please enter a 4-digit PIN',
    'pin.digits' => 'PIN must be exactly 4 digits',
    'pin.confirmed' => 'PIN confirmation does not match',
    'pin_confirmation.required' => 'Please confirm your PIN',
]);

        $user->transaction_pin = Hash::make($request->pin); //hash the pin. never store pin in plain text 
        $user->pin_set_at = now();   //get the user time the transaction pin was set 
        $user->save();   //save the user's pin to database

        // forces the user to login 
       Auth::logout(); //log the user out after setting pin so that the user will be redirected to login and not straight to dashboard

       //invalidate the session and csrf token
       $request->session()->invalidate();  //destroy the session
       $request->session()->regenerateToken(); //regenerate csrf lo

        // redirect the user to login page with success message
        return redirect()->route('login')
            ->with('success', 'Transaction PIN set successfully. Please login to continue');
    }
}