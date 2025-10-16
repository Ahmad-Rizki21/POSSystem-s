<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->select('id', 'name', 'email', 'role_id', 'created_at')->get();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier',
        ]);

        // Get role_id from role name
        $roleId = \App\Models\Role::where('name', $request->role)->first()?->id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
        ]);

        return response()->json($user->load('role'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user->load('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,cashier',
        ]);

        // Get role_id from role name
        $roleId = \App\Models\Role::where('name', $request->role)->first()?->id;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $roleId,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json($user->load('role'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            if ($user->id === auth()->id()) {
                return response()->json(['error' => 'You cannot delete your own account.'], 400);
            }

            // Check if user exists before attempting deletion
            if (!$user->exists) {
                return response()->json(['error' => 'User does not exist.'], 404);
            }

            $user->delete();

            return response()->json(['message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'auth_id' => auth()->id() ?? null,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Failed to delete user.'], 500);
        }
    }
}