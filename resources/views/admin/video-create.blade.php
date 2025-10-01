@extends('layouts.admin')

@section('title', 'Add Video - VideoEarn')
@section('page-title', 'Add Video')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 max-w-3xl">
    <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <input name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Heroism / Mysteries ...">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL</label>
                <input name="youtube_url" type="url" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">YouTube ID (optional)</label>
                <input name="youtube_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                <input name="duration" type="number" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Points Value</label>
                <input name="points_value" type="number" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
