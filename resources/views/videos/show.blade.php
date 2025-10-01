@extends('layouts.user')

@section('title', $video->title . ' - VideoEarn')
@section('page-title', $video->title)

@section('content')
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <!-- Video Player -->
                    <div class="mb-6">
                        <div class="aspect-video bg-gray-900 rounded-lg overflow-hidden relative">
                            @if($video->youtube_embed_url)
                                <iframe 
                                    id="youtube-player"
                                    src="{{ $video->youtube_embed_url }}&enablejsapi=1&controls=0&showinfo=0&rel=0&modestbranding=1" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="w-full h-full">
                                </iframe>
                                <!-- Custom overlay to control video -->
                                <div id="video-overlay" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <div class="mb-4">
                                            <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold mb-2">{{ $video->title }}</h3>
                                        <p class="text-sm text-gray-300 mb-4">Click "Start Watching" below to begin</p>
                                        <div class="text-xs text-gray-400">
                                            Duration: {{ $video->formatted_duration }} | Points: {{ $video->points_value }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-center h-full text-white">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium mb-2">Video Not Available</p>
                                        <p class="text-sm text-gray-400 mb-4">Unable to load video player</p>
                                        @if($video->youtube_url)
                                            <a href="{{ $video->youtube_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                                Watch on YouTube
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Video Info -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $video->category }}
                            </span>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $video->formatted_duration }}
                                </span>
                                <span class="flex items-center font-semibold text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    {{ $task->points_earned }} points
                                </span>
                            </div>
                        </div>

                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $video->title }}</h1>
                        <p class="text-gray-600 mb-4">{{ $video->description }}</p>
                    </div>

                    <!-- Watch Controls -->
                    @if(!$task->is_completed)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Watch Instructions</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Watch the entire video to completion</li>
                                            <li>Do not skip or fast forward</li>
                                            <li>Points will be awarded automatically when video ends</li>
                                            <li>You can only earn points once per video</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button id="start-watch-btn" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                                Start Watching
                            </button>
                            <a href="{{ route('videos.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition-colors border border-gray-300">
                                Back to Tasks
                            </a>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Video Completed!</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p>Congratulations! You have earned <strong>{{ $task->points_earned }} points</strong> for watching this video.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <a href="{{ route('videos.index') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                                View More Tasks
                            </a>
                            <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition-colors border border-gray-300">
                                Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        let player;
        let watchId = null;
        let isWatching = false;
        let videoDuration = {{ $video->duration ?? 60 }}; // Video duration in seconds
        let requiredWatchTime = Math.floor(videoDuration * 0.8); // Must watch 80% of video
        let progressInterval = null;
        let currentWatchTime = 0;
        let watchStartTime = null;
        
        // YouTube API ready
        function onYouTubeIframeAPIReady() {
            console.log('YouTube API ready');
            try {
                player = new YT.Player('youtube-player', {
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            } catch (error) {
                console.error('Error creating YouTube player:', error);
            }
        }
        
        // Player ready
        function onPlayerReady(event) {
            console.log('YouTube player ready');
        }
        
        // Player state change
        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.PLAYING && isWatching) {
                console.log('Video started playing');
            } else if (event.data === YT.PlayerState.PAUSED) {
                console.log('Video paused');
            } else if (event.data === YT.PlayerState.ENDED) {
                console.log('Video ended');
                if (isWatching) {
                    completeVideoWatch();
                }
            }
        }
        
        // Wait for page to load
        window.onload = function() {
            console.log('Video page loaded');
            
            const startBtn = document.getElementById('start-watch-btn');
            if (startBtn) {
                startBtn.onclick = function() {
                    startVideoWatch();
                };
            }
        };
        
        // Start video watch function
        async function startVideoWatch() {
            if (isWatching) return;
            
            const button = document.getElementById('start-watch-btn');
            const originalText = button.textContent;
            
            try {
                // Update button state
                button.disabled = true;
                button.textContent = 'Starting...';
                button.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-blue-700');
                button.classList.add('bg-gradient-to-r', 'from-yellow-600', 'to-yellow-700');
                
                // Create watch session
                const response = await fetch(`{{ route('videos.start-watch', $video) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.watch_id) {
                    watchId = data.watch_id;
                    isWatching = true;
                    watchStartTime = Date.now();
                    currentWatchTime = 0;
                    
                    // Update button
                    button.textContent = 'Watching...';
                    button.classList.remove('bg-gradient-to-r', 'from-yellow-600', 'to-yellow-700');
                    button.classList.add('bg-gradient-to-r', 'from-green-600', 'to-green-700');
                    
                    // Hide overlay
                    const overlay = document.getElementById('video-overlay');
                    if (overlay) {
                        overlay.style.display = 'none';
                    }
                    
                    // Start the video
                    if (player && player.playVideo) {
                        console.log('Starting video playback');
                        player.playVideo();
                    } else {
                        console.log('YouTube player not ready, using fallback timer');
                    }
                    
                    // Show progress indicator
                    showProgressIndicator();
                    
                    // Start timer
                    startWatchTimer();
                    
                    console.log('Watch session started successfully');
                    
                } else {
                    throw new Error(data.error || 'Failed to start watching');
                }
            } catch (error) {
                console.error('Error starting watch:', error);
                button.disabled = false;
                button.textContent = originalText;
                button.classList.remove('bg-gradient-to-r', 'from-yellow-600', 'to-yellow-700');
                button.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-blue-700');
                alert('Error: ' + error.message);
            }
        }
        
        // Start watch timer
        function startWatchTimer() {
            console.log('Starting watch timer for', requiredWatchTime, 'seconds');
            currentWatchTime = 0;
            
            progressInterval = setInterval(function() {
                if (!isWatching) {
                    clearInterval(progressInterval);
                    return;
                }
                
                // Get actual video time if player is available
                if (player && player.getCurrentTime) {
                    try {
                        const videoTime = player.getCurrentTime();
                        if (videoTime > 0) {
                            currentWatchTime = Math.floor(videoTime);
                        } else {
                            currentWatchTime += 1;
                        }
                    } catch (error) {
                        currentWatchTime += 1;
                    }
                } else {
                    currentWatchTime += 1;
                }
                
                updateProgress();
                
                // Check if user has watched enough
                if (currentWatchTime >= requiredWatchTime) {
                    console.log('Required watch time reached');
                    clearInterval(progressInterval);
                    completeVideoWatch();
                }
            }, 1000); // Update every second
        }
        
        // Update progress display
        function updateProgress() {
            const percentage = (currentWatchTime / videoDuration) * 100;
            const progressBar = document.getElementById('watch-progress');
            const timeDisplay = document.getElementById('watch-time');
            
            if (progressBar) {
                const safePercentage = Math.min(Math.max(percentage, 0), 100);
                progressBar.style.width = safePercentage + '%';
                progressBar.textContent = Math.floor(safePercentage) + '%';
            }
            
            if (timeDisplay) {
                const minutes = Math.floor(currentWatchTime / 60);
                const seconds = currentWatchTime % 60;
                const totalMinutes = Math.floor(videoDuration / 60);
                const totalSeconds = videoDuration % 60;
                timeDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')} / ${totalMinutes}:${totalSeconds.toString().padStart(2, '0')}`;
            }
        }
        
        // Show progress indicator
        function showProgressIndicator() {
            const watchInstructions = document.querySelector('.bg-yellow-50');
            if (watchInstructions) {
                watchInstructions.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-yellow-800">Watching Progress</h3>
                            <div class="mt-2">
                                <div class="flex justify-between text-xs text-yellow-700 mb-1">
                                    <span id="watch-time">0:00 / ${Math.floor(videoDuration/60)}:${(videoDuration%60).toString().padStart(2, '0')}</span>
                                    <span>${Math.floor(requiredWatchTime/60)}:${(requiredWatchTime%60).toString().padStart(2, '0')} required</span>
                                </div>
                                <div class="bg-yellow-200 rounded-full h-2 mb-2">
                                    <div id="watch-progress" class="bg-yellow-600 h-2 rounded-full transition-all duration-300" style="width: 0%">0%</div>
                                </div>
                                <p class="text-xs text-yellow-700">
                                    Watch ${requiredWatchTime} seconds to earn {{ $video->points_value }} points
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }
        }
        
        // Complete video watch
        async function completeVideoWatch() {
            if (!watchId) return;
            
            console.log('Completing video watch...');
            
            try {
                const response = await fetch(`{{ route('videos.complete-watch', $video) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        watch_id: watchId,
                        watch_duration: currentWatchTime,
                        required_duration: requiredWatchTime
                    })
                });
                
                const data = await response.json();
                console.log('Complete response:', data);
                
                if (data.success) {
                    isWatching = false;
                    if (progressInterval) {
                        clearInterval(progressInterval);
                        progressInterval = null;
                    }
                    
                    // Update button
                    const button = document.getElementById('start-watch-btn');
                    button.textContent = 'Completed!';
                    button.classList.remove('bg-gradient-to-r', 'from-green-600', 'to-green-700');
                    button.classList.add('bg-gradient-to-r', 'from-green-500', 'to-green-600');
                    button.disabled = true;
                    
                    // Show success message
                    showSuccessMessage(data.points_earned, data.dollar_value, currentWatchTime);
                    
                    // Update progress to 100%
                    updateProgress();
                    
                } else {
                    throw new Error(data.error || 'Failed to complete watch');
                }
            } catch (error) {
                console.error('Error completing watch:', error);
                alert('Failed to complete video watch. Please try again.');
            }
        }
        
        // Show success message
        function showSuccessMessage(points, dollarValue, watchTime) {
            const watchInstructions = document.querySelector('.bg-yellow-50');
            if (watchInstructions) {
                const minutes = Math.floor(watchTime / 60);
                const seconds = watchTime % 60;
                
                watchInstructions.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Video Completed Successfully!</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Congratulations! You earned <strong>${points} points</strong> ($${dollarValue.toFixed(2)})</p>
                                <p class="mt-1">Watch time: ${minutes}:${seconds.toString().padStart(2, '0')} | Your balance has been updated.</p>
                            </div>
                        </div>
                    </div>
                `;
                watchInstructions.classList.remove('bg-yellow-50', 'border-yellow-200');
                watchInstructions.classList.add('bg-green-50', 'border-green-200');
            }
        }
    </script>
@endsection
