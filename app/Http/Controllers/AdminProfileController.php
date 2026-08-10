<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    /**
     * Display Profile & User Management page (for direct route).
     */
    public function index()
    {
        $user = Auth::user();
        $siteSettings = Setting::pluck('value', 'key')->all();
        return \Inertia\Inertia::render('Admin/Profile', [
            'user' => $user,
            'siteSettings' => $siteSettings
        ]);
    }


    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return \Inertia\Inertia::render('Admin/Users', [
            'users' => $users,
        ]);
    }

    /**
     * Update Logged-In User Profile Information (Name, Email, Phone, Address, Avatar).
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        // Handle Avatar File Upload
        if ($request->hasFile('avatar_file')) {
            $avatarDir = public_path('uploads/avatars');
            if (!File::isDirectory($avatarDir)) {
                File::makeDirectory($avatarDir, 0755, true, true);
            }

            // Remove old custom avatar if exists
            if (!empty($user->avatar) && File::exists(public_path(ltrim($user->avatar, '/')))) {
                @unlink(public_path(ltrim($user->avatar, '/')));
            }

            $file = $request->file('avatar_file');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($avatarDir, $filename);
            $user->avatar = '/uploads/avatars/' . $filename;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->city = $validated['city'] ?? null;
        $user->country = $validated['country'] ?? 'Bangladesh';
        $user->designation = $validated['designation'] ?? null;
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar_url' => $user->avatar_url,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'city' => $user->city,
                    'country' => $user->country,
                    'designation' => $user->designation,
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Profile information updated successfully!');
    }

    /**
     * Remove Profile Picture.
     */
    public function removeAvatar(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!empty($user->avatar) && File::exists(public_path(ltrim($user->avatar, '/')))) {
            @unlink(public_path(ltrim($user->avatar, '/')));
        }

        $user->avatar = null;
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile avatar removed.',
                'avatar_url' => $user->avatar_url
            ]);
        }

        return redirect()->back()->with('success', 'Profile photo removed successfully.');
    }

    /**
     * Update Logged-In User Password.
     */
    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password does not match our records.'
                ], 422);
            }
            return redirect()->back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated securely!'
            ]);
        }

        return redirect()->back()->with('success', 'Your password has been changed securely.');
    }

    /**
     * User Management: Create new Admin / Staff User.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'is_admin' => 'nullable|boolean',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar_file')) {
            $avatarDir = public_path('uploads/avatars');
            if (!File::isDirectory($avatarDir)) {
                File::makeDirectory($avatarDir, 0755, true, true);
            }
            $file = $request->file('avatar_file');
            $filename = 'avatar_user_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move($avatarDir, $filename);
            $avatarPath = '/uploads/avatars/' . $filename;
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'Staff',
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'avatar' => $avatarPath,
            'is_admin' => $request->has('is_admin') ? 1 : 1,
        ]);

        return redirect()->back()->with('success', 'New system user / administrator account created successfully!');
    }

    /**
     * User Management: Update User Role / Status.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|min:6',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * User Management: Delete User.
     */
    public function deleteUser($id)
    {
        if (Auth::id() == $id) {
            return redirect()->back()->withErrors(['error' => 'You cannot delete your own logged-in account.']);
        }

        $user = User::findOrFail($id);

        if (!empty($user->avatar) && File::exists(public_path(ltrim($user->avatar, '/')))) {
            @unlink(public_path(ltrim($user->avatar, '/')));
        }

        $user->delete();

        return redirect()->back()->with('success', 'User account deleted from system.');
    }
}
