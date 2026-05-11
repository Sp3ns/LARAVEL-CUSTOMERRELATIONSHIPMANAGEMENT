@extends('layouts.app')
@section('title', 'Add Lead')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('leads.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back to Leads
        </a>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Create New Lead</h2>
        <form method="POST" action="{{ route('leads.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                    <select name="source" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select</option>
                        @foreach (\App\Models\Lead::SOURCES as $src)
                            <option value="{{ $src }}" {{ old('source') === $src ? 'selected' : '' }}>{{ $src }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Models\Lead::STATUSES as $s)
                            <option value="{{ $s }}" {{ old('status', 'New') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Models\Lead::PRIORITIES as $p)
                            <option value="{{ $p }}" {{ old('priority', 'Medium') === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Value ($)</label>
                    <input type="number" name="expected_value" value="{{ old('expected_value') }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                    <select name="assigned_user_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Unassigned</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('leads.index') }}" class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Create Lead</button>
            </div>
        </form>
    </div>
</div>
@endsection
