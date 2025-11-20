<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user mr-2 text-blue-600"></i>{{ __('Name') }}
            </label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="w-full px-4 py-3 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Enter your full name"
            />
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2 text-blue-600"></i>{{ __('Email Address') }}
            </label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username"
                placeholder="your.email@example.com"
            />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="ml-2 underline text-sm text-blue-600 hover:text-blue-800 font-medium">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>{{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="bep20_address" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-wallet mr-2 text-blue-600"></i>{{ __('BEP20 Wallet Address') }}
            </label>
            @if($user->hasBoundWallet())
                <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-gray-300 rounded-lg">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-800 font-mono break-all">{{ $user->bep20_address }}</p>
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-lock mr-1"></i>Locked
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Wallet binding is locked. Contact support to request an update.
                    </p>
                </div>
            @else
                <input 
                    id="bep20_address" 
                    name="bep20_address" 
                    type="text" 
                    class="w-full px-4 py-3 border {{ $errors->has('bep20_address') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-mono text-sm" 
                    value="{{ old('bep20_address', $user->bep20_address) }}" 
                    placeholder="0x..." 
                    autocomplete="off"
                />
                @error('bep20_address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1.5"></i>This address will be locked once saved.
                </p>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <div>
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-medium flex items-center"
                >
                    <i class="fas fa-check-circle mr-2"></i>{{ __('Profile updated successfully!') }}
                </p>
            @endif
        </div>
        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg transition-all font-medium flex items-center gap-2">
            <i class="fas fa-save"></i>
            {{ __('Save Changes') }}
        </button>
    </div>
</form>
