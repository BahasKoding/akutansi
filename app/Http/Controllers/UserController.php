<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        // Hitung statistik
        $totalUsers = $users->count();
        $adminUsers = $users->where('role', 'admin')->count();
        $staffUsers = $users->whereIn('role', ['user', 'manager'])->count();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'adminUsers',
            'staffUsers'
        ))->with('title', 'Users Management')
          ->with('breadcrumb', [
              ['title' => 'Users', 'url' => route('users.index')]
          ]);
    }

    public function store(Request $request)
    {
        if (!Gate::allows('admin-access')) {
            return response()->view('errors.unauthorized', [], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin,manager',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        if (!Gate::allows('admin-access')) {
            return response()->view('errors.unauthorized', [], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,manager',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (!Gate::allows('admin-access')) {
            return response()->view('errors.unauthorized', [], 403);
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
