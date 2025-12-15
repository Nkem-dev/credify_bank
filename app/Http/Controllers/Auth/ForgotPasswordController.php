<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // Show the email form
    public function showEmailForm() 
    { //show the forgot password index view
        return view('auth.passwords.forgotpassword');
    }

    // Submit email form
    public function submitEmail(Request $request) 
    {
        // Validate input
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We could not find a user with this email address.',
        ]);

        try {
            DB::beginTransaction(); //begin task

            // Get the user that the email is a match in he database with the one in the request
            $user = User::where('email', $request->email)->first();

            // if the user does not exist return with error message
            if (!$user) {
                return redirect()->back()->withErrors(['email' => 'We could not find a user with this email address']);
            }

            // Generate token and OTP
            $resetToken = Str::random(40);
            $otp = rand(100000, 999999);

            // Update user with reset data
            $user->password_reset_token = $resetToken;
            $user->password_reset_otp = $otp;
            $user->password_reset_token_expires_at = now()->addMinutes(20);
            $user->save(); //save to database

            // Send email
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp, $user));

            //  dd('Email sent');
            DB::commit(); //commit to database

           //redirect to otp form with succcess message
            return redirect()->route('password.confirm.code', ['token' => $resetToken])
                ->with('success', 'We have sent an OTP to your email to reset your password');

        } catch (Exception $e) {
            DB::rollback();
            // dd($e->getMessage());
            Log::error('Forgot password error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'email' => $request->email,
            ]);
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    // Show OTP confirmation form
    public function showConfirmationCode($token)
    {
        // find user with valid token. checks for the user that matches the token in the url. it also checks if the token has not expired
        $user = User::where('password_reset_token', $token)
            ->where('password_reset_token_expires_at', '>', now())
            ->first();

            // if no user was found
        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid or expired token. Please request a new password reset.');
        }

        // helper method that masks the email
        $maskedEmail = $this->maskEmail($user->email);
        // Convert expiration time to UNIX timestamp for JavaScript
        $expiresAtUnix = $user->password_reset_token_expires_at->timestamp * 1000;

        // return view with email, token, otp count down in frontend
        return view('auth.passwords.confirm-code', [
            'token' => $token, 
            'email' => $maskedEmail,
            'otp_expires_at_unix' => $expiresAtUnix
        ]);
    }

    // Verify OTP
    public function verifyPasswordOtp(Request $request, $token)
    {
        try {
            // validate input
            $request->validate([
                'otp' => 'required|numeric|digits:6'
            ], [
                'otp.required' => 'Please enter the verification code',
                'otp.digits' => 'Verification code must be 6 digits',
            ]);

             // find user with valid token. checks for the user that matches the token in the url. it also checks if the token has not expired
            $user = User::where('password_reset_token', $token)
                ->where('password_reset_token_expires_at', '>', now())
                ->first();

                // if the user does not exist
            if (!$user) {
                return redirect()->back()->with('error', 'Invalid or expired token.');
            }

         

            // OTP is valid, proceed to password reset form
            return redirect()->route('password.reset.form', ['token' => $token])
                ->with('success', 'OTP verified successfully. Please enter your new password.');

        } catch (Exception $e) {
              
            Log::error('OTP verification error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }

    // Resend OTP
    public function resendOtp(Request $request, $token)
    {
        try {
            // get the  user that the reset token in database matches with the one in the url
            $user = User::where('password_reset_token', $token)->first();

            // if here is no user that is a match, return with error message
            if (!$user) {
                return redirect()->route('password.request')
                    ->with('error', 'Invalid token. Please start the password reset process again.');
            }

            // Generate new OTP and extend expiration
            $otp = rand(100000, 999999);
            $user->password_reset_otp = $otp;
            $user->password_reset_token_expires_at = now()->addMinutes(20);
            $user->save(); //save to database

            // Send new OTP to user's email
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp, $user));

            // return with success message
            return redirect()->back()->with('success', 'A new OTP has been sent to your email.');

        } catch (Exception $e) { //if it fails
            Log::error('Resend OTP error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to resend OTP. Please try again.');
        }
    }

    // Show reset password form
    public function showResetPasswordForm($token)
    {
        // look for the user in the database that the password reset token matches with the one in the url and has not expire yet
        $user = User::where('password_reset_token', $token)
            ->where('password_reset_token_expires_at', '>', now())
            ->first();

            // if the user does not exist, return with error message
        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid or expired token. Please request a new password reset.');
        }

        // return the view with token
        return view('auth.passwords.reset-password', ['token' => $token]);
    }

    // Submit new password
    public function submitResetPassword(Request $request, $token)
    {
        // validate the inputs
        $request->validate([
            'password' => 'required|min:8|max:40|confirmed',
        ], [
            'password.required' => 'Please enter a new password',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        // look for the user in the database that the password reset token matches with the one in the url and has not expire yet
        $user = User::where('password_reset_token', $token)
            ->where('password_reset_token_expires_at', '>', now())
            ->first();

            // if the user does not exist, return with error messge
        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid or expired password reset token.');
        }

        // update password in the database
        try {
            DB::transaction(function () use ($user, $request) {
                $user->password = Hash::make($request->password); //hash password
                $user->password_reset_token = null;
                $user->password_reset_otp = null;
                $user->password_reset_token_expires_at = null;
                $user->save();
            });

            return redirect()->route('login')
                ->with('success', 'Password successfully reset. You can now login with your new password.');

        } catch (Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while resetting your password. Please try again.');
        }
    }

    // Helper function to mask email
    private function maskEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        list($localPart, $domain) = explode('@', $email);
        $localLength = strlen($localPart);

        if ($localLength <= 2) {
            return '***@' . $domain;
        }

        $maskedLocal = $localPart[0] . str_repeat('*', $localLength - 2) . $localPart[$localLength - 1];
        return $maskedLocal . '@' . $domain;
    }
}