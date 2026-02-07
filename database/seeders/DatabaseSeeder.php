<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Video;
use App\Models\LearningModule;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin Default (jika belum ada)
        User::firstOrCreate(
            ['email' => 'admin@siaga.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Buat User Biasa (untuk testing)
        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Budi Warga',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 3. Buat Kategori Utama
        $cats = [
            [
                'name' => 'Pra-Bencana',
                'desc' => 'Persiapan dan mitigasi sebelum bencana terjadi.',
                'icon' => 'fas fa-shield-alt'
            ],
            [
                'name' => 'Tanggap Darurat',
                'desc' => 'Panduan keselamatan saat bencana sedang berlangsung.',
                'icon' => 'fas fa-first-aid'
            ],
            [
                'name' => 'Pasca-Bencana',
                'desc' => 'Langkah pemulihan dan rehabilitasi setelah bencana.',
                'icon' => 'fas fa-hands-helping'
            ],
        ];

        foreach ($cats as $c) {
            $category = Category::create([
                'name' => $c['name'],
                'slug' => Str::slug($c['name']),
                'description' => $c['desc'],
                'icon' => $c['icon'],
                'is_active' => true,
            ]);

            // 4. Buat Topik untuk setiap Kategori
            $topics = ['Gempa Bumi', 'Banjir Bandang', 'Kebakaran Rumah', 'Tsunami'];
            
            foreach ($topics as $t) {
                $topic = Topic::create([
                    'category_id' => $category->id,
                    'name' => $t,
                    'slug' => Str::slug($t . '-' . $category->slug), // Unik per kategori
                    'description' => "Panduan lengkap menghadapi {$t} pada fase {$c['name']}.",
                ]);

                // 5. Buat Video Dummy
                Video::create([
                    'topic_id' => $topic->id,
                    'title' => "Cara Selamat dari {$t}",
                    'slug' => Str::slug("video-{$t}-{$category->slug}"),
                    'youtube_id' => 'dQw4w9WgXcQ', // Dummy ID (Rickroll biar gak error player)
                    'description' => 'Video edukasi animasi tentang keselamatan diri.',
                    'duration' => rand(3, 15),
                    'is_featured' => rand(0, 1),
                ]);

                Video::create([
                    'topic_id' => $topic->id,
                    'title' => "Tutorial Tas Siaga {$t}",
                    'slug' => Str::slug("video-tas-{$t}-{$category->slug}"),
                    'youtube_id' => 'jNQXAC9IVRw', // Dummy ID 2
                    'description' => 'Apa saja yang harus dibawa saat evakuasi.',
                    'duration' => rand(5, 10),
                ]);

                // 6. Buat Modul Dummy
                LearningModule::create([
                    'topic_id' => $topic->id,
                    'title' => "Buku Saku {$t}",
                    'slug' => Str::slug("modul-{$t}-{$category->slug}"),
                    'file_path' => 'dummy.pdf', // File dummy
                    'file_type' => 'pdf',
                    'description' => "Panduan tertulis lengkap (PDF) untuk {$t}.",
                    'is_featured' => rand(0, 1),
                ]);
            }
        }
    }
}