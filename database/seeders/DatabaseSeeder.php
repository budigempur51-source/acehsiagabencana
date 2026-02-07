<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Video;
use App\Models\LearningModule;
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
        // 1. BUAT AKUN ADMIN & USER UTAMA
        // ------------------------------------------------------------------
        User::create([
            'name' => 'Admin Siaga',
            'email' => 'admin@siagabencana.com',
            'password' => Hash::make('password'), // Ganti password nanti
            'email_verified_at' => now(),
            'role' => 'admin', // Pastikan kolom 'role' ada di tabel users, atau sesuaikan logika adminmu
        ]);

        User::create([
            'name' => 'Budi Warga',
            'email' => 'user@warga.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Akun Admin & User berhasil dibuat!');


        // 2. BUAT KATEGORI BENCANA
        // ------------------------------------------------------------------
        $catBanjir = Category::create([
            'name' => 'Banjir',
            'slug' => 'banjir',
            'description' => 'Panduan lengkap keselamatan menghadapi bencana banjir, mulai dari persiapan hingga pemulihan.',
            'is_active' => true,
            'icon' => null, // Nanti upload manual di admin jika butuh
        ]);

        $catGempa = Category::create([
            'name' => 'Gempa Bumi',
            'slug' => 'gempa-bumi',
            'description' => 'Mitigasi dan langkah penyelamatan diri saat terjadi guncangan gempa bumi.',
            'is_active' => true,
        ]);

        $catTsunami = Category::create([
            'name' => 'Tsunami',
            'slug' => 'tsunami',
            'description' => 'Edukasi deteksi dini tsunami dan jalur evakuasi menuju dataran tinggi.',
            'is_active' => true,
        ]);

        $catKebakaran = Category::create([
            'name' => 'Kebakaran',
            'slug' => 'kebakaran',
            'description' => 'Teknik pemadaman api dini dan evakuasi kebakaran rumah tangga.',
            'is_active' => true,
        ]);

        $this->command->info('✅ Kategori Bencana berhasil dibuat!');


        // 3. BUAT TOPIK PEMBELAJARAN (Siklus Bencana)
        // ------------------------------------------------------------------
        
        // Topik untuk BANJIR
        $topikBanjirPra = Topic::create([
            'category_id' => $catBanjir->id,
            'name' => 'Siaga Pra Bencana',
            'slug' => 'banjir-pra-bencana',
            'description' => 'Apa yang harus disiapkan SEBELUM banjir datang? Pelajari Tas Siaga Bencana.',
        ]);

        $topikBanjirSaat = Topic::create([
            'category_id' => $catBanjir->id,
            'name' => 'Saat Banjir Melanda',
            'slug' => 'banjir-saat-kejadian',
            'description' => 'Langkah kritis penyelamatan diri dan keluarga saat air mulai naik.',
        ]);

        $topikBanjirPasca = Topic::create([
            'category_id' => $catBanjir->id,
            'name' => 'Pasca Bencana & Pemulihan',
            'slug' => 'banjir-pasca-bencana',
            'description' => 'Membersihkan lumpur, mencegah penyakit, dan kembali ke rumah dengan aman.',
        ]);

        // Topik untuk GEMPA
        $topikGempaMitigasi = Topic::create([
            'category_id' => $catGempa->id,
            'name' => 'Mitigasi Struktur Bangunan',
            'slug' => 'gempa-mitigasi',
            'description' => 'Membangun rumah tahan gempa dan mengamankan perabotan.',
        ]);

        $topikGempaEvakuasi = Topic::create([
            'category_id' => $catGempa->id,
            'name' => 'Teknik Evakuasi Mandiri',
            'slug' => 'gempa-evakuasi',
            'description' => 'Metode Drop, Cover, Hold On dan cara keluar gedung dengan selamat.',
        ]);


        // 4. BUAT KONTEN VIDEO (REAL YOUTUBE LINKS)
        // ------------------------------------------------------------------

        // Video BANJIR
        Video::create([
            'topic_id' => $topikBanjirPra->id,
            'title' => 'Animasi Edukasi: Kenapa Banjir Bisa Terjadi?',
            'slug' => Str::slug('Animasi Edukasi Kenapa Banjir Bisa Terjadi'),
            'url' => 'https://www.youtube.com/watch?v=Iw3ws0BKGwQ', // BNPB Animation
            'description' => 'Video animasi singkat dari BNPB menjelaskan faktor penyebab banjir.',
            'is_featured' => true,
        ]);

        Video::create([
            'topic_id' => $topikBanjirPra->id,
            'title' => 'Tas Siaga Bencana: Apa Saja Isinya?',
            'slug' => Str::slug('Tas Siaga Bencana Apa Saja Isinya'),
            'url' => 'https://www.youtube.com/watch?v=KPbmlF0ODg0', // Si Mita BNPB
            'description' => 'Tutorial lengkap menyiapkan Tas Siaga Bencana (TSB) untuk kondisi darurat.',
            'is_featured' => true,
        ]);

        Video::create([
            'topic_id' => $topikBanjirSaat->id,
            'title' => 'Langkah Penyelamatan Diri Saat Banjir',
            'slug' => Str::slug('Langkah Penyelamatan Diri Saat Banjir'),
            'url' => 'https://www.youtube.com/watch?v=UNydhyYeW98', // Mitigasi Banjir
            'description' => 'Panduan visual tindakan cepat saat air masuk ke rumah.',
            'is_featured' => false,
        ]);

        Video::create([
            'topic_id' => $topikBanjirPasca->id,
            'title' => 'Waspada Penyakit Pasca Banjir',
            'slug' => Str::slug('Waspada Penyakit Pasca Banjir'),
            'url' => 'https://www.youtube.com/watch?v=nJeNMDZvcrQ', // SiAGA Banjir
            'description' => 'Tips kesehatan dan kebersihan setelah banjir surut.',
            'is_featured' => false,
        ]);

        // Video GEMPA
        Video::create([
            'topic_id' => $topikGempaEvakuasi->id,
            'title' => 'Cara Berlindung Saat Gempa (Drop, Cover, Hold On)',
            'slug' => Str::slug('Cara Berlindung Saat Gempa'),
            'url' => 'https://www.youtube.com/watch?v=USYiVuwbcwk', // First Aid Gempa
            'description' => 'Teknik standar internasional untuk melindungi kepala dan organ vital saat gempa.',
            'is_featured' => true,
        ]);

        Video::create([
            'topic_id' => $topikGempaMitigasi->id,
            'title' => 'Edukasi Bencana Gempa Bumi untuk Keluarga',
            'slug' => Str::slug('Edukasi Bencana Gempa Bumi'),
            'url' => 'https://www.youtube.com/watch?v=8-ZZ3uzZkp0', // BNPB Edukasi
            'description' => 'Video ramah keluarga untuk mengajarkan anak-anak tentang bahaya gempa.',
            'is_featured' => false,
        ]);


        // 5. BUAT MODUL E-BOOK (DUMMY)
        // ------------------------------------------------------------------
        LearningModule::create([
            'topic_id' => $topikBanjirPra->id,
            'title' => 'Buku Saku Tanggap Tangkas Tangguh',
            'slug' => 'buku-saku-bnpb',
            'description' => 'Panduan resmi BNPB tentang kesiapsiagaan menghadapi berbagai bencana di Indonesia.',
            'file_path' => 'modules/files/dummy-buku-saku.pdf', // File dummy, pastikan handle error 404 nanti
            'is_featured' => true,
        ]);

        LearningModule::create([
            'topic_id' => $topikBanjirSaat->id,
            'title' => 'Checklist Evakuasi Mandiri',
            'slug' => 'checklist-evakuasi',
            'description' => 'Daftar barang dan dokumen penting yang wajib dibawa saat sirine berbunyi.',
            'file_path' => 'modules/files/checklist.pdf',
            'is_featured' => false,
        ]);

        LearningModule::create([
            'topic_id' => $topikGempaEvakuasi->id,
            'title' => 'Poster Jalur Evakuasi',
            'slug' => 'poster-jalur',
            'description' => 'Contoh rambu jalur evakuasi yang benar untuk gedung bertingkat.',
            'file_path' => 'modules/files/poster.pdf',
            'is_featured' => false,
        ]);

        $this->command->info('✅ Semua data dummy (Video & Modul) berhasil di-generate!');
    }
}