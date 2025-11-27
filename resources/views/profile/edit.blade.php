@extends('layouts.user')

@section('title', 'Profile - Earn Quest')
@section('page-title', 'Profile')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Profile Information Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Profile Settings</h2>
                        <p class="text-sm text-blue-100 mt-1">Update your profile information</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/30">
                        <i class="fas fa-user-circle text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-700 to-green-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Security Settings</h2>
                        <p class="text-sm text-emerald-100 mt-1">Change your password to keep your account secure</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/30">
                        <i class="fas fa-lock text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
@endsection
