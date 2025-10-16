<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::with('role')->select('id', 'name', 'email', 'role_id', 'created_at')->get();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = \App\Models\Role::all();

        return Inertia::render('Users/Create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('role');

        return Inertia::render('Users/Show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load('role');
        $roles = \App\Models\Role::all();

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Debug logging
        \Log::info('Attempting to delete user', [
            'user_to_delete_id' => $user->id,
            'user_to_delete_name' => $user->name,
            'authenticated_user_id' => auth()->id(),
            'authenticated_user_name' => auth()->user()->name,
            'authenticated_user_role' => auth()->user()->role ? auth()->user()->role->name : 'no role'
        ]);

        if ($user->id === auth()->id()) {
            \Log::warning('User attempted to delete own account', ['user_id' => $user->id]);
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        \Log::info('User deleted successfully', ['deleted_user_id' => $user->id]);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}