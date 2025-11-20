@extends('layouts.user')

@section('title', 'Profile - Earn Quest')
@section('page-title', 'Profile')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Profile Information Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white">Profile Information</h2>
                        <p class="text-sm text-blue-100 mt-1">Update your account's profile information and email address</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-circle text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white">Update Password</h2>
                        <p class="text-sm text-emerald-100 mt-1">Ensure your account is using a long, random password to stay secure</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
@endsection
