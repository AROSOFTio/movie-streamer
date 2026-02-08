<?php

namespace Database\Seeders;

use App\Models\Cast;
use App\Models\DownloadRequest;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Plan;
use App\Models\Series;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vj;
use App\Models\VideoFile;
use App\Models\WatchHistory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::disk('local')->put('uploads/demo.mp4', '');

        $admin = User::updateOrCreate(
            ['email' => 'admin@movie.test'],
            [
                'name' => 'Admin User',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@movie.test'],
            [
                'name' => 'Demo User',
                'role' => 'user',
                'password' => Hash::make('password'),
            ]
        );

        $genres = collect([
            'Action', 'Drama', 'Sci-Fi', 'Thriller', 'Comedy', 'Fantasy',
        ])->map(function ($name) {
            return Genre::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        });

        $languages = collect([
            'Luganda',
            'Ateso',
        ])->map(function ($name) {
            return Language::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true]
            );
        });

        $languageMap = $languages->keyBy('slug');

        $vjData = [
            ['name' => 'VJ Sultan', 'language' => 'luganda'],
            ['name' => 'VJ Junior', 'language' => 'luganda'],
            ['name' => 'VJ Oweko', 'language' => 'ateso'],
            ['name' => 'VJ Alex', 'language' => 'ateso'],
            ['name' => 'VJ Max', 'language' => 'luganda'],
        ];

        $vjs = collect($vjData)->map(function ($data) use ($languageMap) {
            $language = $languageMap->get($data['language']);

            return Vj::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'language_id' => $language?->id,
                    'is_active' => true,
                ]
            );
        });

        $casts = collect([
            'Ava Carter', 'Noah Reed', 'Lena Park', 'Darius Cole',
        ])->map(function ($name) {
            return Cast::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'bio' => 'Award-winning talent featured in multiple productions.',
                ]
            );
        });

        $movieData = [
            [
                'title' => 'Neon Horizon',
                'slug' => 'neon-horizon',
                'description' => 'A rogue pilot navigates a collapsing galaxy to save her crew.',
                'language_slug' => 'luganda',
                'year' => 2024,
                'rating' => 8.3,
                'duration' => 128,
                'language' => 'Luganda',
                'country' => 'USA',
                'age_rating' => 'PG-13',
                'featured' => true,
                'poster_path' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba',
                'backdrop_path' => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26',
            ],
            [
                'title' => 'Midnight Signal',
                'slug' => 'midnight-signal',
                'description' => 'A detective uncovers a citywide conspiracy hidden in broadcast waves.',
                'language_slug' => 'ateso',
                'year' => 2023,
                'rating' => 7.9,
                'duration' => 112,
                'language' => 'Ateso',
                'country' => 'USA',
                'age_rating' => 'R',
                'featured' => false,
                'poster_path' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef',
                'backdrop_path' => 'https://images.unsplash.com/photo-1497032205916-ac775f0649ae',
            ],
            [
                'title' => 'Emerald Rift',
                'slug' => 'emerald-rift',
                'description' => 'An elite squad is pulled into a dimension where time fractures.',
                'language_slug' => 'luganda',
                'year' => 2022,
                'rating' => 8.1,
                'duration' => 118,
                'language' => 'Luganda',
                'country' => 'UK',
                'age_rating' => 'PG-13',
                'featured' => false,
                'poster_path' => 'https://images.unsplash.com/photo-1502134249126-9f3755a50d78',
                'backdrop_path' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee',
            ],
        ];

        $movies = collect($movieData)->map(function ($data) use ($genres, $casts, $languageMap, $vjs) {
            $language = $languageMap->get($data['language_slug'] ?? '');
            if ($language) {
                $data['language_id'] = $language->id;
                $data['language'] = $language->name;
            }
            unset($data['language_slug']);

            $movie = Movie::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
            $movie->genres()->sync($genres->random(2)->pluck('id')->toArray());
            $movie->castMembers()->sync($casts->random(2)->pluck('id')->toArray());
            if ($language) {
                $movie->vjs()->sync($vjs->where('language_id', $language->id)->take(2)->pluck('id')->toArray());
            }

            $qualities = ['360p', '720p', '1080p', '2160p'];
            foreach ($qualities as $quality) {
                VideoFile::updateOrCreate(
                    [
                        'owner_type' => Movie::class,
                        'owner_id' => $movie->id,
                        'quality' => $quality,
                    ],
                    [
                        'disk' => 'local',
                        'path' => 'uploads/demo.mp4',
                        'type' => 'mp4',
                        'quality' => $quality,
                        'duration_seconds' => $movie->duration ? $movie->duration * 60 : 7200,
                        'size_bytes' => 1024,
                        'is_primary' => $quality === '1080p',
                    ]
                );
            }

            return $movie;
        });

        $seriesLanguage = $languageMap->get('luganda');
        $series = Series::updateOrCreate([
            'slug' => 'signal-drift',
        ], [
            'title' => 'Signal Drift',
            'slug' => 'signal-drift',
            'description' => 'A crew of hackers uncovers the hidden protocol behind the stream.',
            'year' => 2024,
            'rating' => 8.6,
            'language' => $seriesLanguage?->name ?? 'English',
            'language_id' => $seriesLanguage?->id,
            'country' => 'USA',
            'age_rating' => 'TV-14',
            'featured' => true,
            'poster_path' => 'https://images.unsplash.com/photo-1517602302552-471fe67acf66',
            'backdrop_path' => 'https://images.unsplash.com/photo-1524985069026-dd778a71c7b4',
        ]);
        if ($seriesLanguage) {
            $series->vjs()->sync($vjs->where('language_id', $seriesLanguage->id)->pluck('id')->toArray());
        }

        $episodeLanguage = $languageMap->get('luganda');
        $episode1 = Episode::updateOrCreate([
            'slug' => 'ghost-packet',
        ], [
            'series_id' => $series->id,
            'title' => 'Ghost Packet',
            'slug' => 'ghost-packet',
            'description' => 'A missing signal points to a buried ledger.',
            'season_number' => 1,
            'episode_number' => 1,
            'year' => 2024,
            'rating' => 8.0,
            'duration' => 52,
            'language' => $episodeLanguage?->name ?? 'English',
            'language_id' => $episodeLanguage?->id,
            'country' => 'USA',
            'age_rating' => 'TV-14',
            'featured' => true,
            'poster_path' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d',
            'backdrop_path' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085',
        ]);
        if ($episodeLanguage) {
            $episode1->vjs()->sync($vjs->where('language_id', $episodeLanguage->id)->pluck('id')->toArray());
        }

        VideoFile::updateOrCreate(
            [
                'owner_type' => Episode::class,
                'owner_id' => $episode1->id,
                'quality' => '1080p',
            ],
            [
                'disk' => 'local',
                'path' => 'uploads/demo.mp4',
                'type' => 'mp4',
                'quality' => '1080p',
                'duration_seconds' => 3120,
                'size_bytes' => 1024,
                'is_primary' => true,
            ]
        );

        VideoFile::updateOrCreate(
            [
                'owner_type' => Episode::class,
                'owner_id' => $episode1->id,
                'quality' => '720p',
            ],
            [
                'disk' => 'local',
                'path' => 'uploads/demo.mp4',
                'type' => 'mp4',
                'quality' => '720p',
                'duration_seconds' => 3120,
                'size_bytes' => 1024,
                'is_primary' => false,
            ]
        );

        $plans = [
            ['name' => 'Daily', 'slug' => 'daily', 'price' => 1500, 'interval' => 'daily', 'interval_count' => 1],
            ['name' => 'Weekly', 'slug' => 'weekly', 'price' => 7000, 'interval' => 'weekly', 'interval_count' => 1],
            ['name' => 'Bi-Weekly', 'slug' => 'bi-weekly', 'price' => 14000, 'interval' => 'bi-weekly', 'interval_count' => 1],
            ['name' => 'Monthly', 'slug' => 'monthly', 'price' => 29000, 'interval' => 'monthly', 'interval_count' => 1],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate([
                'slug' => $plan['slug'],
            ], [
                'name' => $plan['name'],
                'slug' => $plan['slug'],
                'price' => $plan['price'],
                'currency' => 'UGX',
                'interval' => $plan['interval'],
                'interval_count' => $plan['interval_count'],
                'description' => $plan['name'].' access to all content.',
                'features' => ['HD streaming', 'Continue Watching', 'Offline requests'],
                'is_active' => true,
            ]);
        }

        $weeklyPlan = Plan::where('slug', 'weekly')->first();

        if ($weeklyPlan) {
            Subscription::updateOrCreate(
                ['user_id' => $user->id, 'plan_id' => $weeklyPlan->id],
                [
                    'status' => Subscription::STATUS_ACTIVE,
                    'starts_at' => now(),
                    'ends_at' => $weeklyPlan->calculateEndsAt(now()),
                ]
            );
        }

        if ($movies->isNotEmpty()) {
            WatchHistory::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'watchable_type' => Movie::class,
                    'watchable_id' => $movies->first()->id,
                ],
                [
                    'last_position_seconds' => 600,
                    'progress_percent' => 25,
                    'last_watched_at' => now()->subDay(),
                ]
            );
        }

        if ($movies->isNotEmpty()) {
            DownloadRequest::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'downloadable_type' => Movie::class,
                    'downloadable_id' => $movies->last()->id,
                ],
                [
                    'status' => DownloadRequest::STATUS_PENDING,
                    'reason' => 'For offline viewing on a trip.',
                ]
            );
        }
    }
}
