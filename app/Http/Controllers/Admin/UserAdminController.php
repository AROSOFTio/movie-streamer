<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::query()->latest()->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'in:user,admin'],
        ]);

        $user->update(['role' => $data['role']]);

        return redirect()->route('admin.users.index')->with('status', 'User role updated.');
    }
}
