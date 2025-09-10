<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::user();

        // Admins can see all users; regular users only see their own record
        $query = User::orderBy('id');
        if ($loggedInUser->role !== 'admin') {
            $query->where('id', $loggedInUser->id);
        }

        $users = $query->paginate(10);

        return view('users.index', compact('users', 'loggedInUser'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // public function show($id)
    // {
    //     $user = User::findOrFail($id);
    //     $this->authorize('view', $user);
    //     activity()->causedBy(Auth::user())->performedOn($user)->withProperties(['viewed_by' => Auth::id()])->log('user.viewed');
    //     return view('users.show', compact('user'));
    // }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
