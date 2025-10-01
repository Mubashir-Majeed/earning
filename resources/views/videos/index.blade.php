@extends('layouts.user')

@section('title', "Today's Video Tasks - VideoEarn")
@section('page-title', "Today's Video Tasks")

@section('content')
        <div class="max-w-7xl mx-auto">
            @if($todayTasks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($todayTasks as $task)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow">
                        @if($task->video->resolved_thumbnail_url)
                        <div class="aspect-video bg-gray-200 overflow-hidden">
                            <img src="{{ $task->video->resolved_thumbnail_url }}" 
                                 alt="{{ $task->video->title }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $task->video->category }}
                                </span>
                                @if($task->is_completed)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        Pending
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">{{ $task->video->title }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $task->video->description }}</p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $task->video->formatted_duration }}
                                </div>
                                <div class="flex items-center text-sm font-semibold text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    {{ $task->points_earned }} points
                                </div>
                            </div>

                            @if($task->is_completed)
                                <div class="flex items-center justify-center p-3 bg-green-50 rounded-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-green-700 font-medium">Video Completed!</span>
                                </div>
                            @else
                                <a href="{{ route('videos.show', $task->video) }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center px-4 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                                    Watch Video
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No video tasks assigned</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back tomorrow for new video tasks.</p>
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
@endsection
