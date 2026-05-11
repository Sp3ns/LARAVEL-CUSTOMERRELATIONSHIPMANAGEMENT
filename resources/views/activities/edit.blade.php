@extends('layouts.app')
@section('title', 'Edit Activity')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('activities.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Activities
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Edit Activity</h2>

        <form method="POST" action="{{ route('activities.update', $activity) }}" class="space-y-5">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Type <span class="text-red-500">*</span>
                    </label>

                    <select name="type" required
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                        @foreach (\App\Models\Activity::TYPES as $t)
                            <option value="{{ $t }}"
                                {{ old('type', $activity->type) === $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Association Selector -->
                <div x-data="{
                        associate: '{{ old('associate', $activity->customer_id ? 'customer' : 'lead') }}'
                    }"
                    class="sm:col-span-2 space-y-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Associate Activity With <span class="text-red-500">*</span>
                        </label>

                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2">
                                <input type="radio"
                                       name="associate"
                                       value="customer"
                                       x-model="associate"
                                       class="text-indigo-600 focus:ring-indigo-500">

                                <span class="text-sm text-gray-700">Customer</span>
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio"
                                       name="associate"
                                       value="lead"
                                       x-model="associate"
                                       class="text-indigo-600 focus:ring-indigo-500">

                                <span class="text-sm text-gray-700">Lead</span>
                            </label>
                        </div>
                    </div>

                    <!-- Customer Dropdown -->
                    <div x-show="associate === 'customer'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Select Customer
                        </label>

                        <select name="customer_id"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                            <option value="">Choose Customer</option>

                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}"
                                    {{ old('customer_id', $activity->customer_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lead Dropdown -->
                    <div x-show="associate === 'lead'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Select Lead
                        </label>

                        <select name="lead_id"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                            <option value="">Choose Lead</option>

                            @foreach ($leads as $l)
                                <option value="{{ $l->id }}"
                                    {{ old('lead_id', $activity->lead_id) == $l->id ? 'selected' : '' }}>
                                    {{ $l->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Description <span class="text-red-500">*</span>
                    </label>

                    <textarea name="description"
                              rows="4"
                              required
                              class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $activity->description) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('activities.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm">
                    Update Activity
                </button>
            </div>
        </form>
    </div>
</div>
@endsection