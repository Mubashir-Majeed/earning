@extends('layouts.admin')

@section('title', 'Videos Management - VideoEarn')
@section('page-title', 'Videos Management')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Video Management</h2>
            <p class="text-gray-600">Manage all videos and their earning potential</p>
        </div>
        <div class="flex space-x-3">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Export Videos
            </button>
            <a href="{{ route('admin.videos.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Video
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Videos</label>
                <input type="text" placeholder="Search by title or category..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    <option value="heroism">Heroism</option>
                    <option value="nation-builders">Nation Builders</option>
                    <option value="histories">Histories</option>
                    <option value="mysteries">Mysteries</option>
                    <option value="education">Education</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($videos as $video)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow">
                <!-- Video Thumbnail -->
                <div class="relative">
                    @if($video->resolved_thumbnail_url)
                        <img src="{{ $video->resolved_thumbnail_url }}" alt="{{ $video->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-video text-white text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $video->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $video->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="absolute bottom-3 left-3">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-black/70 text-white">
                            <i class="fas fa-clock mr-1"></i>{{ $video->duration }}
                        </span>
                    </div>
                </div>

                <!-- Video Info -->
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $video->title }}</h3>
                        <div class="flex space-x-1 ml-3">
                            <button class="text-blue-600 hover:text-blue-900 transition-colors" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $video->description }}</p>

                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-tag mr-1"></i>
                            <span class="capitalize">{{ $video->category }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-coins mr-1"></i>
                            <span class="font-medium text-green-600">{{ $video->points_value }} points</span>
                        </div>
                    </div>

                    <!-- Video Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Max Watches/Day</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $video->max_watches_per_day }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Assigned Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $video->assigned_date ? \Carbon\Carbon::parse($video->assigned_date)->format('M d') : 'Not set' }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ $video->youtube_url ?: ($video->youtube_id ? 'https://www.youtube.com/watch?v='.$video->youtube_id : route('videos.show', $video)) }}" target="_blank" rel="noopener" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors text-center">
                            <i class="fas fa-eye mr-1"></i>Preview
                        </a>
                        <a href="{{ route('admin.videos.stats', $video) }}" class="flex-1 px-3 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors text-center">
                            <i class="fas fa-chart-bar mr-1"></i>Stats
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500">
                    <i class="fas fa-video text-6xl mb-4"></i>
                    <p class="text-xl">No videos found</p>
                    <p class="text-sm">Add your first video to get started</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($videos->hasPages())
        <div class="flex justify-center">
            {{ $videos->links() }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Videos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $videos->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-video text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Videos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $videos->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-play text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Points Available</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($videos->sum('points_value')) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Avg Points/Video</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($videos->avg('points_value'), 1) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection