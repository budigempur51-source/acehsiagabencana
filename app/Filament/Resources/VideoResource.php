<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use App\Models\Topic;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?string $navigationLabel = 'Video Edukasi';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Video')
                            ->schema([
                                // LOGIC: Pilih Kategori Dulu (Virtual Field)
                                Forms\Components\Select::make('category_id')
                                    ->label('Pilih Kategori')
                                    ->options(Category::query()->where('is_active', true)->pluck('name', 'id'))
                                    ->live() // Bikin reaktif
                                    ->afterStateUpdated(fn (Set $set) => $set('topic_id', null)) // Reset topik jika kategori berubah
                                    ->required()
                                    ->dehydrated(false), // Jangan simpan field ini ke database

                                // LOGIC: Topik menyesuaikan Kategori
                                Forms\Components\Select::make('topic_id')
                                    ->label('Topik Pembahasan')
                                    ->options(fn (Get $get): Collection => Topic::query()
                                        ->where('category_id', $get('category_id'))
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->relationship('topic', 'name'),

                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Video')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, $set) => 
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi Singkat')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Sumber Video')
                            ->schema([
                                Forms\Components\TextInput::make('url')
                                    ->label('Link YouTube')
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->url()
                                    ->required() // Form mewajibkan, tapi data lama mungkin ada yang null
                                    ->columnSpanFull()
                                    ->helperText('Pastikan link valid dari YouTube.'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Tampilan & Status')
                            ->schema([
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->label('Thumbnail Video')
                                    ->image()
                                    ->directory('videos/thumbnails')
                                    ->imageEditor(),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Video Unggulan?')
                                    ->helperText('Video unggulan akan tampil di slider halaman depan.')
                                    ->onColor('warning')
                                    ->offColor('gray'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Cover')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder-video.png')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),

                Tables\Columns\TextColumn::make('topic.category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topik')
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('topic_id')
                    ->relationship('topic', 'name')
                    ->label('Filter Topik')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Status Unggulan'),
            ])
            ->actions([
                // PERBAIKAN DI SINI: Hapus strict return type 'string'
                Tables\Actions\Action::make('visit')
                    ->label('Tonton')
                    ->icon('heroicon-m-play')
                    ->url(fn (Video $record) => $record->url) // Type hint dihapus agar aman jika null
                    ->openUrlInNewTab()
                    ->visible(fn (Video $record) => !empty($record->url)), // Tombol hilang jika URL kosong
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}