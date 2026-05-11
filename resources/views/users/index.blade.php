@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">User Management</h2>
            <p class="text-sm text-gray-500">Manage system users and roles</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add User
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Customers</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Leads</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $u->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><x-badge :label="$u->role" /></td>
                        <td class="px-6 py-4 text-right text-sm text-gray-600">{{ $u->customers_count }}</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-600">{{ $u->leads_count }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $u) }}" class="text-gray-400 hover:text-indigo-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')
                                        <button class="text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($users->hasPages()) <div class="px-6 py-3 border-t">{{ $users->links() }}</div> @endif
    </div>
</div>
@endsection
