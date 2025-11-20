<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div class="space-y-4">
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-key mr-2 text-emerald-600"></i>{{ __('Current Password') }}
            </label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="w-full px-4 py-3 border {{ $errors->updatePassword->has('current_password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" 
                autocomplete="current-password"
                placeholder="Enter your current password"
            />
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-emerald-600"></i>{{ __('New Password') }}
            </label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="w-full px-4 py-3 border {{ $errors->updatePassword->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" 
                autocomplete="new-password"
                placeholder="Enter your new password"
            />
            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-gray-500 flex items-center">
                <i class="fas fa-info-circle mr-1.5"></i>Use a strong password with at least 8 characters
            </p>
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-emerald-600"></i>{{ __('Confirm New Password') }}
            </label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="w-full px-4 py-3 border {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" 
                autocomplete="new-password"
                placeholder="Confirm your new password"
            />
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <div>
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-medium flex items-center"
                >
                    <i class="fas fa-check-circle mr-2"></i>{{ __('Password updated successfully!') }}
                </p>
            @endif
        </div>
        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-lg hover:from-emerald-700 hover:to-emerald-800 shadow-md hover:shadow-lg transition-all font-medium flex items-center gap-2">
            <i class="fas fa-save"></i>
            {{ __('Update Password') }}
        </button>
    </div>
</form>
