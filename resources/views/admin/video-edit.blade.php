@extends('layouts.admin')

@section('title', 'Edit Video - Earn Quest')
@section('page-title', 'Edit Video')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 w-full">
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">Step 1</p>
                    <h3 class="text-lg font-semibold text-gray-900">Video Details</h3>
                    <p class="text-sm text-gray-500">Basic information your members will see.</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-white shadow-sm text-gray-600">Required</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input name="title" value="{{ old('title', $video->title) }}" required class="w-full px-4 py-2 border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input name="category" value="{{ old('category', $video->category) }}" required class="w-full px-4 py-2 border {{ $errors->has('category') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Heroism / Mysteries ...">
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (seconds)</label>
                    <input name="duration" type="number" min="1" value="{{ old('duration', $video->duration) }}" required class="w-full px-4 py-2 border {{ $errors->has('duration') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Date</label>
                    <input name="assigned_date" type="date" value="{{ old('assigned_date', $video->assigned_date ? $video->assigned_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Leave empty to make the video available immediately.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Watches / Day</label>
                    <input name="max_watches_per_day" type="number" min="1" value="{{ old('max_watches_per_day', $video->max_watches_per_day ?? 1) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Default 1 watch / user">
                </div>
                <div class="flex items-center space-x-2 bg-white border border-gray-200 rounded-lg px-4 py-3">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $video->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Mark as Active</p>
                        <p class="text-xs text-gray-500">Inactive videos remain hidden from users.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 p-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                <div>
                    <p class="text-xs uppercase tracking-wide text-blue-600 font-semibold">Step 2</p>
                    <h3 class="text-lg font-semibold text-gray-900">Video Source</h3>
                    <p class="text-sm text-gray-500">Drop a YouTube/TikTok link and we'll extract the ID automatically.</p>
                </div>
                <div class="hidden md:flex items-center gap-2 text-xs text-gray-500">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">YouTube</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-pink-50 text-pink-600">TikTok</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Video URL <span class="text-red-500">*</span></label>
                    <input name="youtube_url" type="url" value="{{ old('youtube_url', $video->youtube_url) }}" required class="w-full px-4 py-2 border {{ $errors->has('youtube_url') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="https://www.youtube.com/watch?v=... • https://www.youtube.com/shorts/... • https://www.tiktok.com/@user/video/...">
                    @error('youtube_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Supports YouTube videos/shorts and TikTok links. The ID is auto-extracted.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Video ID (optional)</label>
                    <input name="youtube_id" value="{{ old('youtube_id', $video->youtube_id) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Auto-extracted from URL">
                    @error('youtube_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Override if you want to control the ID manually.</p>
                </div>
                <div class="bg-gray-50 border border-dashed border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                    <p class="font-semibold text-gray-800 mb-1">Tip: Copy share links directly</p>
                    <ul class="list-disc list-inside space-y-1 text-xs text-gray-500">
                        <li>YouTube: <span class="font-mono text-gray-700">https://youtu.be/&lt;id&gt;</span></li>
                        <li>YouTube Shorts: <span class="font-mono text-gray-700">https://youtube.com/shorts/&lt;id&gt;</span></li>
                        <li>TikTok: <span class="font-mono text-gray-700">https://www.tiktok.com/@user/video/&lt;id&gt;</span></li>
                    </ul>
                </div>
                <div>
                    <label class="block text-sm	font-medium text-gray-700 mb-1">Thumbnail URL</label>
                    <input name="thumbnail_url" type="url" value="{{ old('thumbnail_url', $video->thumbnail_url) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="https://...">
                    <p class="text-xs text-gray-500 mt-1">Optional if you upload an image below.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Thumbnail</label>
                    <input name="thumbnail" type="file" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, or WEBP up to 2&nbsp;MB.</p>
                    @if($video->thumbnail_url)
                        <p class="mt-2 text-xs text-gray-600">Current: <a href="{{ $video->thumbnail_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $video->thumbnail_url }}</a></p>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 p-5 bg-gradient-to-br from-slate-50 to-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs uppercase tracking-wide text-emerald-600 font-semibold">Step 3</p>
                    <h3 class="text-lg font-semibold text-gray-900">Earning Settings</h3>
                    <p class="text-sm text-gray-500">Customize payouts per package. Defaults apply if fields are left empty.</p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold">$</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Default Dollar Value</label>
                    <input name="dollar_value" type="number" step="0.01" min="0.01" value="{{ old('dollar_value', $video->dollar_value) }}" required class="w-full px-4 py-2 border {{ $errors->has('dollar_value') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('dollar_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Used if a package-specific value is not provided.</p>
                </div>
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Starter Package ($35)</label>
                    <input name="dollar_value_starter" type="number" step="0.01" min="0.01" value="{{ old('dollar_value_starter', $video->dollar_value_starter ?? $video->dollar_value) }}" class="w-full px-4 py-2 border {{ $errors->has('dollar_value_starter') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional">
                    @error('dollar_value_starter')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Growth Package ($50)</label>
                    <input name="dollar_value_growth" type="number" step="0.01" min="0.01" value="{{ old('dollar_value_growth', $video->dollar_value_growth ?? $video->dollar_value) }}" class="w-full px-4 py-2 border {{ $errors->has('dollar_value_growth') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional">
                    @error('dollar_value_growth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pro Package ($100)</label>
                    <input name="dollar_value_pro" type="number" step="0.01" min="0.01" value="{{ old('dollar_value_pro', $video->dollar_value_pro ?? $video->dollar_value) }}" class="w-full px-4 py-2 border {{ $errors->has('dollar_value_pro') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional">
                    @error('dollar_value_pro')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2 lg:col-span-3 bg-white border border-emerald-100 rounded-xl p-4 text-sm text-gray-600">
                    <p class="font-semibold text-gray-800 mb-1">Need inspiration?</p>
                    <p class="text-xs text-gray-500">Most admins start with a 1.0x / 1.2x / 1.5x split across Starter, Growth, and Pro packages.</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 p-5">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Additional Notes</h3>
            <p class="text-sm text-gray-500 mb-3">Describe the story or instructions for this video task.</p>
            <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional but recommended for community videos.">{{ old('description', $video->description) }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
            <a href="{{ route('admin.videos') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-center">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update Video
            </button>
        </div>
    </form>
</div>
@endsection
