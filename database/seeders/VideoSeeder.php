<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Video;
use Carbon\Carbon;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = [
            [
                'title' => 'The Brave Hero\'s Journey',
                'description' => 'An inspiring tale of courage and determination in the face of adversity.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Heroism',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 180, // 3 minutes
                'points_value' => 15,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Building Our Nation',
                'description' => 'Stories of great leaders who shaped our country\'s history.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Nation Builders',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 240, // 4 minutes
                'points_value' => 20,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Ancient Mysteries Revealed',
                'description' => 'Uncover the secrets of ancient civilizations and their hidden knowledge.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Mysteries',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 300, // 5 minutes
                'points_value' => 25,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Historical Battles',
                'description' => 'Relive the greatest battles that changed the course of history.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Histories',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 360, // 6 minutes
                'points_value' => 30,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Modern Heroes',
                'description' => 'Contemporary heroes making a difference in today\'s world.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Heroism',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 200, // 3.3 minutes
                'points_value' => 18,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Unsolved Mysteries',
                'description' => 'The world\'s most puzzling mysteries that continue to baffle experts.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Mysteries',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 280, // 4.7 minutes
                'points_value' => 22,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Revolutionary Leaders',
                'description' => 'The visionaries who led revolutions and changed societies.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Nation Builders',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 320, // 5.3 minutes
                'points_value' => 28,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Medieval Legends',
                'description' => 'Tales of knights, kings, and legendary figures from medieval times.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Histories',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 260, // 4.3 minutes
                'points_value' => 24,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Supernatural Encounters',
                'description' => 'Strange and unexplained supernatural phenomena from around the world.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Mysteries',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 340, // 5.7 minutes
                'points_value' => 32,
                'assigned_date' => Carbon::today(),
            ],
            [
                'title' => 'Civil Rights Movement',
                'description' => 'The heroes who fought for equality and justice for all.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'category' => 'Nation Builders',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duration' => 380, // 6.3 minutes
                'points_value' => 35,
                'assigned_date' => Carbon::today(),
            ],
        ];

        foreach ($videos as $videoData) {
            Video::create($videoData);
        }
    }
}