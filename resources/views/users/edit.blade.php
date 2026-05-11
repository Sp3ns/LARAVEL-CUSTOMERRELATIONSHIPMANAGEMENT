@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6"><a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back</a></div>
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ $user->name }}</h2>
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="sales" {{ old('role', $user->role) === 'sales' ? 'selected' : '' }}>Sales Staff</option>
                    <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password <span class="text-xs text-gray-400">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm">Update User</button>
            </div>
        </form>
    </div>
</div>
@endsection
