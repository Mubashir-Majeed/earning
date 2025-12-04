<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('patch')

    <!-- Profile Picture Section -->
    <div class="flex flex-col items-center mb-8">
        <div class="relative">
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-200 shadow-lg bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                @if($user->profile_picture)
                    @php
                        // Handle both old storage paths and new public paths
                        $imagePath = $user->profile_picture;
                        if (strpos($imagePath, 'images/') === 0) {
                            $imageUrl = asset('public/' . $imagePath);
                        } else {
                            $imageUrl = asset('storage/' . $imagePath);
                        }
                    @endphp
                    <img src="{{ $imageUrl }}" alt="Profile Picture" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-purple-500">
                        <i class="fas fa-user text-white text-5xl"></i>
                    </div>
                @endif
            </div>
            <label for="profile_picture" class="absolute bottom-0 right-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-blue-700 transition-colors border-2 border-white">
                <i class="fas fa-camera text-white text-sm"></i>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" onchange="previewImage(this)">
            </label>
        </div>
        <p class="mt-4 text-sm text-gray-600 text-center">
            <i class="fas fa-info-circle mr-1"></i>Click the camera icon to upload your profile picture
        </p>
        @error('profile_picture')
            <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-6">
        <!-- Name Field -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-user mr-2 text-blue-600"></i>{{ __('Full Name') }}
            </label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="w-full px-4 py-3 border-2 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Enter your full name"
            />
            @error('name')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email Display (Read-only) -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2 text-gray-400"></i>{{ __('Email Address') }}
            </label>
            <div class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-600">
                {{ $user->email }}
            </div>
            <p class="mt-2 text-xs text-gray-500 flex items-center">
                <i class="fas fa-lock mr-1"></i>Email address cannot be changed
            </p>
        </div>
    </div>

    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
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
        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all font-semibold flex items-center gap-2">
            <i class="fas fa-save"></i>
            {{ __('Save Changes') }}
        </button>
    </div>
</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = input.closest('.relative').querySelector('img');
            const icon = input.closest('.relative').querySelector('.fa-user');
            const div = input.closest('.relative').querySelector('.w-32');
            
            if (img) {
                img.src = e.target.result;
            } else if (icon) {
                // Remove icon and add image
                const iconParent = icon.closest('div');
                iconParent.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="w-full h-full object-cover">`;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
