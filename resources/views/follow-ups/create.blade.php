@extends('layouts.app')
@section('title', 'New Follow-Up')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('follow-ups.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back to Follow-Ups
        </a>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Create New Follow-Up</h2>
        <form method="POST" action="{{ route('follow-ups.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('due_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Models\FollowUp::STATUSES as $s)
                            <option value="{{ $s }}" {{ old('status', 'Pending') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assign To <span class="text-red-500">*</span></label>
                    <select name="user_id" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id', auth()->id()) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link to Customer</label>
                    <select name="customer_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">None</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $selectedCustomerId ?? '') == $c->id ? 'selected' : '' }}>{{ $c->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link to Lead</label>
                    <select name="lead_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">None</option>
                        @foreach ($leads as $l)
                            <option value="{{ $l->id }}" {{ old('lead_id', $selectedLeadId ?? '') == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('follow-ups.index') }}" class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm">Create Follow-Up</button>
            </div>
        </form>
    </div>
</div>
@endsection
