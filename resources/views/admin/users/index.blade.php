@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Users</h1>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->role }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                                @csrf
                                <select name="role" class="rounded-lg border border-white/10 bg-white/5 px-2 py-1 text-xs">
                                    <option value="user" @selected($user->role === 'user')>user</option>
                                    <option value="admin" @selected($user->role === 'admin')>admin</option>
                                </select>
                                <button class="rounded-lg bg-brand px-3 py-1 text-xs font-semibold text-black hover:bg-brand-dark">Save</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>{{ $users->links() }}</div>
@endsection
