<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\EmailVerification;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();


        
        $user = User::find(Auth::id());

        // check if user needs verification
        if(!$user->email_verified_at){
            Auth::logout();

            try{
                DB::beginTransaction();

                // Generate new OTP and token
            $otp = random_int(100000, 999999);
            $verificationToken = Str::random(40);

            $user->email_verification_otp = $otp;
            $user->email_verification_token = $verificationToken;
            $user->save();

           

            Mail::to($user->email)->send(new EmailVerification($otp));
             DB::commit();

            return redirect()->route('otp.verify', ['token' => $verificationToken])->with('info', 'Please verify your email. A verification code has been sent.');

            } catch(Exception $e){
                DB::rollback();
                 dd($e->getMessage());
                return redirect()->route('login')->with('error', 'Verification process failed. Please try again');

            }

        }
        

       // gives you new login session that prevents hackers
        $request->session()->regenerate();

        return $this->sendLoginResponse($request);

        // return redirect()->intended(route('dashboard', absolute: false));
    }

     protected function sendLoginResponse(): RedirectResponse
    {

        $user = Auth::user();

        // CHECK IF USER HAS SET TRANSACTION PIN
        if (!$user->hasSetPin()) {
            return redirect()->route('pin.setup')
                ->with('info', 'Please set your transaction PIN to continue.');
        }

        $redirectRoutes = [
            'access-admin-dashboard' => 'admin.dashboard',
            'access-user-dashboard' => 'user.dashboard',
            'access-customer_care-dashboard' => 'customer_care.dashboard',
        ];

        foreach ($redirectRoutes as $gate => $route) {
            if(Gate::allows($gate)) {
                return redirect()->intended(route($route, absolute: false));
            }
        }

        Auth::logout();
        return redirect()->route('login')->with('error', 'Your account does not have any access to the dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
