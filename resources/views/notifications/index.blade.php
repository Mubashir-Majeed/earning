@extends('layouts.user')

@section('title', 'Notifications - Earn Quest')
@section('page-title', 'Notifications')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">All Notifications</h2>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Mark all as read
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($notifications as $notification)
                    <a href="{{ $notification->link ?? '#' }}" class="block px-6 py-4 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ 
                                    $notification->type === 'deposit' ? 'bg-blue-100 text-blue-600' : 
                                    ($notification->type === 'withdrawal' || $notification->type === 'earnings' ? 'bg-green-100 text-green-600' : 
                                    ($notification->type === 'referral' ? 'bg-purple-100 text-purple-600' : 
                                    ($notification->type === 'admin' ? 'bg-red-100 text-red-600' : 
                                    ($notification->type === 'package' ? 'bg-yellow-100 text-yellow-600' : 
                                    ($notification->type === 'level' ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600'))))) 
                                }}">
                                    <i class="fas {{ $notification->icon }}"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $notification->title }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 ml-4">
                                        @if(!$notification->is_read)
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        @endif
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}" class="inline" onclick="event.stopPropagation();">
                                            @csrf
                                            <button type="submit" class="text-xs text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline" onclick="event.stopPropagation();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No notifications yet</p>
                    </div>
                @endforelse
            </div>
            
            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
