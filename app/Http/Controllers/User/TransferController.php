<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\NigerianBank;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferController extends Controller
{
        //  return view for transfer
     public function index()
    {
        return view('user.transfer.index');
    }


    // return view for internal transfer form
    public function internalCreate()
    {

        return view('user.transfer.internaltransfer');
    }

 

     // find the user you are sending money to for internal transfer(credify) using account number, email, name or phone number
    public function lookup(Request $request)
    {
        $request->validate([   //validate the inputs
            'identifier' => 'required|string',
            'method' => 'required|in:account_number,email,phone,username'
        ]);

        $method = $request->method; //method of search 
        $identifier = $request->identifier; 
        $currentUserId = auth()->id(); //get the id of the current user(the person sending the money)

        // Build query based on lookup method
        $query = User::query();

        // uses switch method to search the current column
        switch ($method) {
            case 'account_number': // if method is account no search user by account number
                $query->where('account_number', $identifier);
                break;
            case 'email': //if method of seacrh is email search user by email
                $query->where('email', $identifier);
                break;
            case 'phone':  //if method of earch is by phone no search user by phone number
                $query->where('phone', $identifier);
                break;
            case 'username':  //if method of search is by name search user by username
                $query->where('username', $identifier);
                break;
        }

     // get the first user in the database that is a match according to search method
        $user = $query->first();

        // Check if user exists and if the user does not exist in the database return error message 
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Prevent Current user from transferring money to themselves
        if ($user->id === $currentUserId) { //if the user id that you search is equals matches with the id of the user initiating the transfer(current user)
            return response()->json([ //return error message
                'success' => false,
                'message' => 'You cannot transfer money to yourself'
            ], 400);
        }

        // else return the details of the user that the user wants to make transfer to
        return response()->json([ //return success 
            'success' => true,
            'user' => [
                'id' => $user->id, //user id
                'name' => $user->name, //user name
                'account_number' => $user->account_number, //user account number
                'email' => $user->email //user email
            ]
        ]);
    }

   
// check if the current user that wants to intitiate transfer has enough money. it checks if transfer is possible before asking for pin
public function validateAmount(Request $request)
    {
        $request->validate([ //validate inputs
            'recipient_id' => 'required|exists:users,id',  
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $sender = auth()->user(); //get authenticated user(the sender)
        $recipient = User::findOrFail($request->recipient_id); //go to the user table using the user model and find the recipient (receiver) using recipient's id

        // Prevent self-transfer
        if ($recipient->id === $sender->id) { //if the recipient's id is equals to the senders id
            return response()->json([
                'success' => false, //prevent the sender from sending money to themselves
                'message' => 'You cannot send money to yourself.'
            ], 400);
        }

        $amount = (float) $request->amount; //converts the amount to a real number

        // Check balance
        if ($sender->balance < $amount) { //if the sender's balance is less than the amount
            return response()->json([
                'success' => false, //return error message saying insufficient balane
                'message' => 'Insufficient balance. Your balance is ₦' . number_format($sender->balance, 2) 
            ], 400);
        }

        // if everythng is succesful and amount has been verified , the transcation pin input will pop up
        return response()->json(['success' => true]);
    }


    // send the money
    public function internalStore(Request $request)
    {
        // Save everything the user sent(amount, recipient, pin, etc) into laravel logs for debugging purpose
        Log::info('Internal Transfer Request:', $request->all());

        // Check if this is from the PIN modal
        $isFromModal = $request->has('prevalidated');

        // Validate inputs
        $rules = [
            'recipient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ];

        // PIN validation
        if ($isFromModal) {
            $rules['pin'] = 'required|digits:4';
        }

        // laravel checks validation rules
        $validated = $request->validate($rules); 

        $sender = auth()->user(); //get the authenticated user(sender)
        $recipient = User::findOrFail($request->recipient_id); // get the receipient's id

        // Prevent self-transfer
        if ($recipient->id === $sender->id) {
            return back()->with('error', 'You cannot send money to yourself.')->withInput();
        }

        // Validate amount again 
        $amount = (float) $request->amount; //convert amount to a number
        if ($sender->balance < $amount) {
            return back()->with('error', 'Insufficient balance. Your balance is ₦' . number_format($sender->balance, 2))->withInput();
        }

        // Verify PIN (only for modal submission)
        if ($isFromModal) { //check if the pin entered matches the pin of the sender in the database and if the  pin does not match return error message
            if (!Hash::check($request->pin, $sender->transaction_pin)) {
                return back()->with('error', 'Incorrect transaction PIN.')->withInput();
            }
        }

        // Start transaction task
        DB::beginTransaction();

        try {
            // generate unique id for reference
            $reference = 'TRF-' . strtoupper(Str::random(12));

            // save to transfers table
            $transfer = Transfer::create([
                'user_id' => $sender->id,
                'reference' => $reference,
                'recipient_id' => $recipient->id,
                'recipient_account_number' => $recipient->account_number,
                'recipient_name' => $recipient->name,
                'recipient_bank_name' => 'Credify Bank',
                'type' => 'internal',
                'amount' => $amount,
                'fee' => 0,
                'total_amount' => $amount,
                'description' => $request->description,
                'status' => 'successful',
                'completed_at' => now(),
            ]);

            // Deduct from sender
            $sender->decrement('balance', $amount);

            // Credit recipient
            $recipient->increment('balance', $amount);

            // Commit transaction
            DB::commit();

            // log success message
            Log::info('Transfer successful:', ['reference' => $reference]);

            // Redirect to receipt with tranfer reference and amount with success message
            return redirect()->route('user.transfers.receipt', $transfer->reference)
                ->with('success', 'Transfer of ₦' . number_format($amount, 2) . ' successful!');

        } catch (Exception $e) { // if there is error
            DB::rollBack(); //rollback changes

            Log::error('Internal Transfer Error:', [ //log error 
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            //return error
            return back()->with('error', 'Transfer failed: ' . $e->getMessage())->withInput();
        }
    }

    public function receipt($reference) //pass reference in url
    {
        $transfer = Transfer::where('reference', $reference) //find a transfer with its reference
            ->where('user_id', auth()->id()) //must belong to the authenticated user(sender)
            ->firstOrFail(); //get thev recored or show error

            // return receipt view and compact with the transfer data
        return view('user.transfer.receipt', compact('transfer'));
    }



     public function externalCreate()
    {
        // $banks = NigerianBank::orderBy('name')->get();
        return view('user.transfer.externaltransfer');
        // , compact('banks')
    }

    public function airtimeCreate()
{
    $user = auth()->user();
    return view('user.airtime.airtime', compact('user'));
}



public function airtimeValidate(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'network' => 'required',
        'amount' => 'required|numeric|min:50',
    ]);

    $user = auth()->user();
    $amount = (float) $request->amount;

    if ($user->balance < $amount) {
        return response()->json([
            'success' => false,
            'message' => 'Insufficient balance: ₦' . number_format($user->balance, 2)
        ], 400);
    }

    return response()->json(['success' => true]);
}


public function airtimeStore(Request $request)
{
    $request->validate([
        'phone' => 'required|string|min:10',
        'network' => 'required|in:mtn,airtel,glo,9mobile',
        'amount' => 'required|numeric|min:50',
        'pin' => 'required|digits:4',
    ]);

    $user = auth()->user();

    // check if pin is the same with the pin in the database
    if (!Hash::check($request->pin, $user->transaction_pin)) {
        return back()->with('error', 'Incorrect PIN.')->withInput();
    }

    $amount = (float) $request->amount;

    if ($user->balance < $amount) {
        return back()->with('error', 'Insufficient balance.')->withInput();
    }

    // Normalize phone number: convert +234 to 0
    $phone = $request->phone;
    if (str_starts_with($phone, '+234')) {
        $phone = '0' . substr($phone, 4);
    } elseif (str_starts_with($phone, '234')) {
        $phone = '0' . substr($phone, 3);
    }


DB::beginTransaction();
    try {
        $reference = 'ATM-' . strtoupper(Str::random(10));

        Transfer::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'type' => 'airtime',
            'amount' => $amount,
            'fee' => 0,
            'total_amount' => $amount,
            'description' => "Airtime Purchase ({$request->network})",
            'status' => 'successful',
            'completed_at' => now(),
            'recipient_account_number' => $phone,
            'recipient_name' => strtoupper($request->network),
        ]);

        $user->decrement('balance', $amount);
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Airtime purchased!',
            'receipt_url' => route('user.transfers.airtime.receipt', $reference)
        ]);

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Airtime failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'error' => 'Purchase failed. Try again.'
        ], 500);
    }
}

public function showAirtimeReceipt($reference)
{
    $transfer = Transfer::where('reference', $reference)
        ->where('user_id', auth()->id())
        ->where('type', 'airtime')
        ->firstOrFail();

    return view('user.airtime.receipt', compact('transfer'));
}

public function dataCreate()
{
    return view('user.data.data'); 
}


public function dataValidate(Request $request)
{
    $request->validate([
        'phone' => [
            'required',
            'string',
            'regex:/^[7-9][0-1]\d{8}$/', // Nigerian format: 10 digits starting with 70, 80, 81, 90, 91
        ],
        'network' => 'required|in:mtn,airtel,glo,9mobile',
        'amount' => 'required|numeric|min:100',
    ], [
        'phone.regex' => 'Invalid phone number format. Use 10 digits starting with 70, 80, 81, 90, or 91',
    ]);

    $user = auth()->user();
    $amount = (float) $request->amount;

    // Check balance
    if ($user->balance < $amount) {
        return response()->json([
            'success' => false,
            'message' => 'Insufficient balance. Your balance is ₦' . number_format($user->balance, 2)
        ], 400);
    }

    // Additional phone validation
    $phone = $request->phone;
    $validPrefixes = ['70', '80', '81', '90', '91'];
    $prefix = substr($phone, 0, 2);

    if (!in_array($prefix, $validPrefixes)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid phone number. Must start with 70, 80, 81, 90, or 91'
        ], 400);
    }

    return response()->json([
        'success' => true,
        'message' => 'Validation successful'
    ]);
}

public function dataStore(Request $request)
{
    // validate the inputs
    $request->validate([
        'phone' => [
            'required',
            'string',
            'regex:/^[7-9][0-1]\d{8}$/',
        ],
        'network' => 'required|in:mtn,airtel,glo,9mobile',
        'amount' => 'required|numeric|min:100',
        'plan' => 'required|string',
        'pin' => 'required|digits:4',
    ], [
        'phone.regex' => 'Invalid phone number format',
    ]);

    $user = auth()->user(); //get the authenticated user

    // pin verification: check if the pin in the input matches the pin in the database
    if (!Hash::check($request->pin, $user->transaction_pin)) {
        return response()->json([
            'success' => false,
            'error' => 'Incorrect transaction PIN'
        ], 400);
    }

    // Check the user's account balance
    $amount = (float) $request->amount; //type cast the amount by converting to an actual number
    if ($user->balance < $amount) { //if the user's account balance is less than the amount the user wants to buy return error message
        return response()->json([
            'success' => false,
            'error' => 'Insufficient balance. Your balance is ₦' . number_format($user->balance, 2)
        ], 400);
    }

    // Decode plan json to an array
    $plan = json_decode($request->plan, true);

    // Check for JSON decode errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        Log::error('JSON decode error', [
            'error' => json_last_error_msg(),
            'plan_data' => $request->plan
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Invalid plan data format'
        ], 400);
    }

    // if the plan is not a valid plan and the size is not valid, return with an error message
    if (!$plan || !isset($plan['size']) || !isset($plan['validity'])) {
        Log::error('Invalid plan structure', ['plan' => $plan]);
        
        return response()->json([
            'success' => false,
            'error' => 'Invalid plan data. Missing size or validity'
        ], 400);
    }

    // Get the value in the phone number field
    $phone = $request->phone; 

    // Begin transaction
    DB::beginTransaction();

    try {
        // get data reference number
        $reference = 'DAT-' . strtoupper(Str::random(12));

        // Create transfer record
        $transfer = Transfer::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'recipient_phone' => $phone,
            'recipient_account_number' => $phone, // Use phone number as account number for data purchases
            'recipient_name' => strtoupper($request->network) . ' Nigeria',
            'recipient_bank_name' => strtoupper($request->network), // Network name as bank name
            'type' => 'data',
            'amount' => $amount,
            'fee' => 0,
            'total_amount' => $amount,
            'description' => "{$plan['size']} {$plan['validity']} Data Bundle - " . strtoupper($request->network),
            'status' => 'successful',
            'completed_at' => now(),
        ]);

        // Verify transfer was created
        if (!$transfer) {
            throw new \Exception('Failed to create transfer record');
        }

        // Deduct amount from user balance
        $newBalance = $user->balance - $amount;
        $user->balance = $newBalance;
        $user->save();

        // Verify balance was updated
        $user->refresh();
        if ($user->balance != $newBalance) {
            throw new \Exception('Failed to update user balance');
        }

        DB::commit();

        Log::info('Data purchase successful', [
            'reference' => $reference,
            'phone' => $phone,
            'network' => $request->network,
            'amount' => $amount,
            'user_id' => $user->id
        ]);

        
        
        // Alternatively, check if route exists
        try {
            $receiptUrl = route('user.transfers.data.receipt', $reference);
        } catch (\Exception $e) {
            Log::warning('Receipt route not found, using fallback URL', ['error' => $e->getMessage()]);
            $receiptUrl = url('/user/data/receipt/' . $reference);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data bundle purchased successfully!',
            'receipt_url' => $receiptUrl
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Data purchase failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => $user->id,
            'phone' => $phone,
            'amount' => $amount,
            'plan' => $plan ?? null
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Data purchase failed. Please try again. Error: ' . $e->getMessage()
        ], 500);
    }
}


public function showDataReceipt($reference)
{
    $transfer = Transfer::where('reference', $reference)
        ->where('user_id', auth()->id())
        ->where('type', 'data')
        ->firstOrFail();

    return view('user.data.receipt', compact('transfer'));
}
   
}
