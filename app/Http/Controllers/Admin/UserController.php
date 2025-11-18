<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
       
        $total = User::count(); //get the total users
        $active = User::where('status', 'active')->count(); //total active users
        $inactive = User::where('status', 'inactive')->count(); //total inactive users
        $suspended = User::where('status', 'suspended')->count(); //total suspended users
        $admins = User::where('role', 'admin')->count(); //total admin 

        // Users query with search and filters
        $query = User::query();

        // Search users with name, email, account number 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
                //   ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by tier
        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        // Filter by KYC status
        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');    // sort by newest first
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(5); //show 5 users per page

        // return the view with users, total users, active users, inactive users, suspended users and admin
        return view('admin.users.index', compact(
            'users',
            'total',
            'active',
            'inactive',
            'suspended',
            'admins'
        ));
    }

}
