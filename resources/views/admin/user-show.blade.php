@extends('layouts.admin')

@section('title', 'User Details - VideoEarn')
@section('page-title', 'User Details')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $user->name }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">Email</p>
            <p class="text-gray-900">{{ $user->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-gray-900">{{ $user->is_active ? 'Active' : 'Inactive' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Balance</p>
            <p class="text-gray-900">${{ number_format($user->balance, 2) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Points</p>
            <p class="text-gray-900">{{ number_format($user->points) }}</p>
        </div>
    </div>
    <div class="mt-6">
        <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Back</a>
    </div>
</div>
@endsection
