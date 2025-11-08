@extends('layouts.admin')

@section('title', 'Add Video - Earn Quest')
@section('page-title', 'Add Video')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 max-w-3xl">
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

    <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input name="title" value="{{ old('title') }}" required class="w-full px-4 py-2 border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <input name="category" value="{{ old('category') }}" required class="w-full px-4 py-2 border {{ $errors->has('category') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Heroism / Mysteries ...">
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL <span class="text-red-500">*</span></label>
                <input name="youtube_url" type="url" value="{{ old('youtube_url') }}" required class="w-full px-4 py-2 border {{ $errors->has('youtube_url') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
                @error('youtube_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">YouTube ID will be automatically extracted from the URL.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">YouTube ID (optional - auto-extracted)</label>
                <input name="youtube_id" value="{{ old('youtube_id') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Auto-extracted from URL">
                @error('youtube_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Leave empty to auto-extract from YouTube URL.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail URL</label>
                <input name="thumbnail_url" type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="https://...">
                <p class="text-xs text-gray-500 mt-1">Optional if you upload an image below.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Thumbnail</label>
                <input name="thumbnail" type="file" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, or WEBP. Max 2 MB.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (seconds)</label>
                <input name="duration" type="number" min="1" value="{{ old('duration') }}" required class="w-full px-4 py-2 border {{ $errors->has('duration') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('duration')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Points Value</label>
                <input name="points_value" type="number" min="1" value="{{ old('points_value') }}" required class="w-full px-4 py-2 border {{ $errors->has('points_value') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('points_value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Date</label>
                <input name="assigned_date" type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max Watches / Day</label>
                <input name="max_watches_per_day" type="number" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-center space-x-2 md:col-span-2">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label class="text-sm text-gray-700">Active</label>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>
        </div>
        <div class="mt-6 flex space-x-3">
            <a href="{{ route('admin.videos') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Video</button>
        </div>
    </form>
</div>
@endsection
