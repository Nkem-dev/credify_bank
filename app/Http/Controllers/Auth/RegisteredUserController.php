<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccountNumberMail;
use App\Mail\EmailVerification;
use App\Models\User;
use Exception;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register'); 
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try{
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'digits:10', 'regex:/^(70|71|80|81|90|91)[0-9]{8}$/', function ($attribute, $value, $fail) {
                    if (str_starts_with($value, '080')) {
                        $fail('Phone number cannot start with 080. Please use 70xxxxxxxxx.');
                    }
                },],

            'dob' => ['required', 'date', 'date_format:Y-m-d', 'before:today', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'gender' => ['required', 'in:male,female,other'],
            ], [
                'name.required' => 'Name field cannot be empty',
                'email.required' => 'Email field cannot be empty',
                'email.unique' => 'This email has already been registered. Please use a different one',
                'dob.required' => 'Please enter your date of birth.',
                'dob.date' => 'Please enter a valid date.',
                'dob.after_or_equal' => 'You must be at least 18 years old to register.',
                'phone.required' => 'Please enter your phone number.',
                'phone.digits'   => 'Phone number must be exactly 11 digits.',
                'password.required' => 'Please enter a password.',
                 'password.confirmed'=> 'Password and confirmation do not match.',
        ]);

         // Check if user is 18+
        // $dob = Carbon::parse($validated['dob']);
        // $age = $dob->age;

        // if ($age < 18) {
        //     return back()
        //         ->withErrors(['dob' => 'You must be at least 18 years old to register.'])
        //         ->withInput();
        // }

         //Generate unique 10-digit account number
        $base = substr($validated['phone'], -7);
        $uniquePart = random_int(100, 999);
        $accountNumber = $uniquePart . $base;

        while (User::where('account_number', $accountNumber)->exists()) {
            $uniquePart = random_int(100, 999);
            $accountNumber = $uniquePart . $base;
        }

        // generate otp and token
         $otp = rand(100000, 999999);
        $verificationToken = Str::random(40);

       $user = User::create([
            'name'                     => $validated['name'],
            'email'                    => $validated['email'],
            'password'                 => Hash::make($validated['password']),
            'phone'                    => $validated['phone'],
            'dob'                      => $validated['dob'],
            'gender'                   => $validated['gender'],
            'account_number'           => $accountNumber,
            'email_verification_otp'   => $otp,
            'email_verification_token' => $verificationToken,
        ]);

        // dd('email sent');
        // Mail::to($user->email)->send(new EmailVerification($otp));

        return redirect()->route('otp.verify', ['token' => $verificationToken])->with('success', 'We have sent you an OTP for your email verification');

       

        
           } catch(Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage()); 
            dd('Failed', $e->getMessage());

           }
    }

   public function showOtpForm($token) 
{
    $user = User::where('email_verification_token', $token)->first();

    if (!$user) {
        abort(404, 'Invalid verification token');
    }

    // If OTP missing or already expired → regenerate
    if (!$user->otp_expires_at || $user->otp_expires_at < now()) {
        $otp = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(20);

        $user->email_verification_otp = $otp;
        $user->otp_expires_at = $expiresAt;
        $user->save();

        // send otp email
        Mail::to($user->email)->send(new EmailVerification($otp));
    }

    $maskedEmail = $this->maskEmail($user->email);

    return view('auth.verify-email', [
        'token' => $token,
        'email' => $maskedEmail,
        'otp_expires_at' => $user->otp_expires_at,
        'otp_expires_at_unix' => $user->otp_expires_at->timestamp * 1000
    ]);
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


    public function otpVerify(Request $request, $token) 
{
    try{
        // validate the otp input and token
        $request->validate([
            'token' => 'required',
            'otp' => 'required|numeric|digits:6'
        ], [
            'otp.required' => 'Please enter the verification code',
        ]);

        // check the first user in the users table under email verification token, if the token passed in the url matches with the one in the database
        $user = User::where('email_verification_token', $token)->first();

        // if there is no user that is a match return back with an error message
        if(!$user) {
            return redirect()->back()->with('error', 'Invalid verification token');
        }

        // if the otp inputed is not strictly equals to the otp request in the database then return back with an error message
        if($user->email_verification_otp !== $request->otp){
            return redirect()->back()->with('error', 'Invalid OTP, please try again');
        }

        // if the otp expiration time is less than the current time then return with a message that otp has expired
        if($user->otp_expires_at < now()) {
            return redirect()->back()->with('error', 'OTP has expired');
        }

        $user->email_verified_at = now(); //returns current date and time that the user's email was verified
        $user->email_verification_token = null; //invalidates the token after use to prevent reuse for security reasons
        $user->email_verification_otp = null;  //invalidates the otp after use to prevent reuse for security reasons
        $user->otp_expires_at = null; //invalidates the otp time after successful otp verification

        $user->save(); //save verified user to the database

        // Send account number email to user
        Mail::to($user->email)->send(new AccountNumberMail($user));

        // if user email is successfully verified, redirect to login page
        // return redirect()->route('login')->with('success', 'Email verification completed. Your account number has been sent to your email. Please login');

        Auth::login($user); // Log in user

      return redirect()->route('pin.setup')
    ->with('success', 'Email verified! Your account number has been sent to your email. Please set your transaction PIN.');

    } catch(Exception $e) {
        return redirect()->back()->with('error', 'An error occurred during verification. Please try again.');
    }
}

public function resendOtp(Request $request, $token)
    {
        // validate the token input

        try{
             $request->validate([
                'token' => 'required',
            ]);

             // Find the first user that the token matches in the database to that of token passed in the url 
            $user = User::where('email_verification_token', $token)->first();

            // if user that is a match is not found, return with an error message
            if (!$user) {
                return redirect()->back()->with('error', 'Invalid verification token');
            }

            // the first condition checks if the user has an otp expiration 

            // Generate new OTP and token
            $otp = random_int(100000, 999999);
            $token = Str::random(40);
            $expiresAt =   $user->otp_expires_at = now()->addMinutes(20);
            // $user->save();

            $user->email_verification_otp = $otp;
            $user->email_verification_token = $token;
            $user->otp_expires_at = $expiresAt;
            $user->save();

            // Send new OTP email
            Mail::to($user->email)->send(new EmailVerification($otp));

            // Return success response with new token route
           return redirect()->back()->with([
                'success' => 'A new OTP has been sent to your email',
                'token' => $token,
                'otp_expires_at' => $expiresAt->timestamp
            ]);

        } catch(Exception $e) {

            Log::error('Failed to resend OTP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to resend OTP. Please try again.');

        }
       
    }

}
