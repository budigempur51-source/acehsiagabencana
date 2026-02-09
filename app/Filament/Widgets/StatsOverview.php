<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\LearningModule;
use App\Models\Topic;
use App\Models\Video;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Mengatur biar kartunya tidak terlalu rapat
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            // KARTU 1: KATEGORI
            Stat::make('Total Kategori', Category::count())
                ->description('Jenis Bencana Alam')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary') // Warna Hijau Emerald
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Grafik Palsu Keren
                ->url(route('filament.admin.resources.categories.index')), // <--- BIAR BISA DIKLIK

            // KARTU 2: TOPIK
            Stat::make('Topik Pembahasan', Topic::count())
                ->description('Sub-materi edukasi')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('info') // Warna Biru
                ->chart([3, 5, 10, 8, 15, 20, 25])
                ->url(route('filament.admin.resources.topics.index')),

            // KARTU 3: VIDEO
            Stat::make('Video Edukasi', Video::count())
                ->description('Konten Visual')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('success') // Hijau Sukses
                ->chart([1, 5, 2, 8, 4, 10, 15])
                ->url(route('filament.admin.resources.videos.index')),

            // KARTU 4: MODUL
            Stat::make('Buku & Modul', LearningModule::count())
                ->description('Bahan Bacaan PDF')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('warning') // Kuning
                ->chart([10, 5, 2, 3, 15, 4, 17])
                ->url(route('filament.admin.resources.learning-modules.index')),
        ];
    }
}