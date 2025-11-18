<?php

namespace App\Http\Controllers\User;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::with('profile')->find(Auth::id());
        return view('user.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validate basic profile updates
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:500',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile && $user->profile->profile_picture) {
                Storage::delete($user->profile->profile_picture);
            }
            
            $validated['profile_picture'] = $request->file('profile_picture')
                ->store('profile-pictures', 'public');
        }

        // Update User
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update or Create Profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $validated['name'],
                'profile_picture' => $validated['profile_picture'] ?? $user->profile?->profile_picture,
                'gender' => $validated['gender'] ?? $user->profile?->gender,
                'bio' => $validated['bio'] ?? $user->profile?->bio,
            ]
        );

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    // public function upgrade(Request $request)
    // {
    //     $user = Auth::user();
        
    //     if ($user->kyc_tier >= 3) {
    //         return redirect()->back()->with('error', 'You have already reached the maximum KYC tier.');
    //     }

    //     // Determine required validation based on current tier
    //     $rules = [];
    //     $successMessage = '';

    //     if ($user->kyc_tier == 1) {
    //         $rules = [
    //             'nin' => 'required|string|size:11|regex:/^[0-9]{11}$/',
    //         ];
    //         $successMessage = 'NIN submitted successfully! Awaiting verification for Tier 2.';
    //     } elseif ($user->kyc_tier == 2) {
    //         $rules = [
    //             'address' => 'required|string|max:255',
    //             'address_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    //         ];
    //         $successMessage = 'Address verification submitted! Awaiting approval for Tier 3.';
    //     }

    //     $validated = $request->validate($rules);

    //     // Handle address proof upload
    //     if (isset($validated['address_proof'])) {
    //         $validated['address_proof'] = $request->file('address_proof')
    //             ->store('kyc-documents', 'public');
    //     }

    //     // Update KYC data
    //     $user->update(array_filter($validated, function($key) {
    //         return in_array($key, ['nin', 'address', 'address_proof']);
    //     }, ARRAY_FILTER_USE_KEY));

    //     // Auto-upgrade tier (in production, this would trigger manual/admin approval)
    //     if ($user->kyc_tier == 1) {
    //         $user->update(['kyc_tier' => 2]);
    //     } elseif ($user->kyc_tier == 2) {
    //         $user->update(['kyc_tier' => 3]);
    //     }

    //     return redirect()->back()->with('success', $successMessage);
    // }
}
