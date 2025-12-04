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
    // show the view of the email form
     public function showEmailForm() 
    {
        return view('auth.passwords.forgotpassword');
    }

    // submit email form
     public function submitEmail(Request $request) {
        // validate input
        $request->validate([
            'email' => 'required|email',
        ]);

        try{

           
            DB::beginTransaction(); //begin task

            // get the first user that the email matches  the email requesting to reset password in the database
            $user = User::where('email', $request->email)->first(); 

            //  dd($user);
            // if there is no email that match in the database
        if(!$user) {
            return redirect()->back()->withErrors(['email'=> 'We could not find a user with this email address']);
        }

        $resetToken = Str::random(40); //generate token
        $otp = rand(100000, 999999); //generate otp

        $user->password_reset_token = $resetToken;
        $user->password_reset_otp = $otp;
        $user->password_reset_token_expires_at = now()->addMinutes(20);

         $user->save();

        Mail::to($user->email)->send(new PasswordResetOtpMail($otp));
        DB::commit();

       

        return redirect()->route('confirm.code', ['token' => $resetToken])->with('success', 'We have sent an OTP to your email to reset your password');

        } catch(Exception $e) {
            DB::rollback();
            Log::error('Forget password error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'email' => $request->email,
            ]);
            return redirect()->back()->with('error', 'Something went wrong, please try again later');
        }
        
    }

    public function showConfirmationCode($token)
    {

         $user = User::where('password_reset_token', $token)->where('password_reset_token_expires_at', '>', now())->first();


         if(!$user) {
            return redirect()->back()->with('error', 'Invalid or expired token');
         }

         $maskedEmail = $this->maskEmail($user->email);

        return view('auth.passwords.confirm-code', [
           
            'token' => $token, 
            'email' => $maskedEmail,
            'expiredAt' => $user->password_reset_token_expires_at
        ]);
          

    }

    public function verifyPasswordOtp(Request $request, $token)
    {
       try{

        $request->validate([
                'token' => 'required',
                'otp' => 'required|numeric|digits:6'
            ], [
                'otp.required' => 'Please enter the verification code',
            ]);

            $user = User::where('password_reset_token', $token)->where('password_reset_token_expires_at', '>', now())->first();

            if(!$user) {
                return redirect()->back()->with('error', 'Invalid or Expired token');
            }

            if($user->password_reset_otp !== $request->otp){
                return redirect()->back()->with('error', 'Invalid OTP, please try again');
            }


            return redirect()->route('password.reset.form', ['token' => $token]);
       } catch(Exception $e){

        dd($e->getMessage());
        return redirect()->back()->with('error', $e->getMessage());

       }
        
    }

    public function showResetPasswordForm($token)
    {

        $user = User::where('password_reset_token', $token)->where('password_reset_token_expires_at', '>', now())->first();

        // dd($user);
        if(!$user) {
            return redirect()->back()->with('error', 'Invalid or Expired token');
        }

        return view('auth.passwords.reset-password', ['token' => $token]);
    }

    // In the controller
    public function submitResetPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|min:8|max:40|confirmed',
        ]);

        $user = User::where('password_reset_token', $token)
            ->where('password_reset_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid or expired password reset token.');
        }

        try {
            // Using DB::transaction with a closure
            DB::transaction(function () use ($user, $request) {
                $user->password = Hash::make($request->password);
                $user->password_reset_token = null;
                $user->password_reset_otp = null;
                $user->password_reset_token_expires_at = null;
                $user->save();
            });

            return redirect()->route('login')->with('success', 'Password successfully reset. You can now login with your new password.');
        } catch (Exception $e) {
       
            return redirect()->back()->with('error', 'An error occurred while resetting your password. Please try again.' . $e->getMessage());
        }
    }

    private function maskEmail($email)
    {
    // validates whether it is an email or not
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //checks if the email is a valid email address if it is invalid the ! turns the condition true and makes sure the function does not process anything that is not a valid email
        return $email; //if $email is not an email return email without masking but if it is a email return email with masking
    }

    // split the email into parts. the email consists of the local part (the part before the @ i.e johndoe) and the domain part (the part after the @ i.e @gmail.com)
    list($localPart, $domain) = explode('@', $email); //explode splits the email at the @ symbol into an array ['johndoe', 'gmail.com']. 
    // list assigns the first part ['johndoe'] to the local part and the ['gmail.com'] to the domain part
    $localLength = strlen($localPart); //gets the length of the local part

    if ($localLength <= 2) { //if the local part is less than equals to 2 that is in a case where the local part of the email is short , it wont try to mask it in a fancy way but would i.e ab@gmail.com
        return '***@' . $domain; //the short email will returns with aestherics then @gmail.com (domain). **@gmail.com
    }

    // mask the local part
    $maskedLocal = $localPart[0] . str_repeat('*', $localLength - 2) . $localPart[$localLength - 1]; //localpart[0] takes the first letter of the local part, str_repeat('*', localLength - 2) creates a string of aestherics and replaces all characters except first and last. 
    return $maskedLocal . '@' . $domain;

    }

}
