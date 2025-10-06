@extends('layouts.user')

@section('title', 'Profile - VideoEarn')
@section('page-title', 'Profile')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="px-6 pt-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Manage Your Account</h2>
                </div>
                
                <!-- Tabs -->
                <div class="mt-6 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button id="tab-profile" class="tab-btn border-blue-600 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-target="#panel-profile">Profile Information</button>
                        <button id="tab-password" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-target="#panel-password">Update Password</button>
                        <button id="tab-danger" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-target="#panel-danger">Delete Account</button>
                    </nav>
                </div>
            </div>

            <!-- Panels -->
            <div class="p-6">
                <div id="panel-profile" class="tab-panel">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div id="panel-password" class="tab-panel hidden">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div id="panel-danger" class="tab-panel hidden">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    (function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const panels = document.querySelectorAll('.tab-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // update tab styles
                tabs.forEach(t => t.classList.remove('border-blue-600','text-blue-600'));
                tabs.forEach(t => t.classList.add('border-transparent','text-gray-500'));
                tab.classList.remove('border-transparent','text-gray-500');
                tab.classList.add('border-blue-600','text-blue-600');

                // toggle panels
                const target = tab.getAttribute('data-target');
                panels.forEach(panel => panel.classList.add('hidden'));
                const active = document.querySelector(target);
                if (active) active.classList.remove('hidden');
            });
        });
    })();
</script>
@endsection
