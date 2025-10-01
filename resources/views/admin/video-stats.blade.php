@extends('layouts.admin')

@section('title', 'Video Stats - '.$video->title)
@section('page-title', 'Video Stats')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-2">{{ $video->title }}</h2>
        <p class="text-sm text-gray-600 mb-4">Category: {{ $video->category }} • Points: {{ $video->points_value }}</p>
        <a href="{{ $video->youtube_url }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg"><i class="fas fa-external-link-alt mr-2"></i>Open on YouTube</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b"><h3 class="font-semibold">Recent Watches</h3></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Started</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                    </tr></thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($watches as $w)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $w->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $w->watch_started_at?->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm">@if($w->is_completed)<span class="text-green-600 font-semibold">Yes</span>@else<span class="text-gray-500">No</span>@endif</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">No watches yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">{{ $watches->links() }}</div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b"><h3 class="font-semibold">Assigned Tasks</h3></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr></thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tasks as $t)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $t->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $t->assigned_date?->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">@if($t->is_completed)<span class="text-green-600 font-semibold">Completed</span>@else<span class="text-yellow-600 font-semibold">Pending</span>@endif</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">No tasks yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">{{ $tasks->links() }}</div>
        </div>
    </div>
</div>
@endsection


