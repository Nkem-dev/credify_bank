<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CustomerCareCredentials;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerCareController extends Controller
{
    // show index view
    public function index()
    { //get users that have the role of customer care
        $customerCareStaff = User::where('role', 'customer_care')
            ->latest()
            ->paginate(20); //20 per page
        
            // return view with customer care details
        return view('admin.customer-care.index', compact('customerCareStaff'));
    }

    // show create customer care form
    public function create()
    { //return the view
        return view('admin.customer-care.create');
    }



 // Submit  customer care form 
    public function store(Request $request)
    {
        // Validate inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'regex:/^[0-9]{10}$/', 'unique:users,phone'],
            'dob' => 'required|date|before:-18 years',
        ], [
            'phone.regex' => 'Phone number must be exactly 10 digits',
            'dob.before' => 'Staff must be at least 18 years old',
        ]);

        try {
            DB::beginTransaction(); //begin task

            $password = Str::random(12); //generate 12 random characters password

          
            // Create the user record 
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->dob = $request->dob;
            $user->password = Hash::make($password);
            $user->role = 'customer_care'; 
            $user->status = 'active';
            $user->balance = 0;
            $user->save();

            // Send credentials to the user's email
            Mail::to($user->email)->send(new CustomerCareCredentials(
                $user->name,
                $user->email,
                $password,
                route('login')
            ));

            DB::commit(); //commit to database

            // return with success message
            return redirect()->route('admin.customer-care.index')
                ->with('success', "Customer care account created for {$user->name}. Login credentials have been sent to {$user->email}");

        } catch (Exception $e) { //if it fails
            DB::rollBack(); //rollback changes
            
            // Log the error for debugging
            Log::error('Customer Care Creation Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create customer care account: ' . $e->getMessage());
        }
    }

      // Show edit form
    public function edit(User $customerCare)
    {
        // Ensure we're editing a customer care staff
        if ($customerCare->role !== 'customer_care') {
            abort(404);
        }

        return view('admin.customer-care.edit', compact('customerCare'));
    }
}
