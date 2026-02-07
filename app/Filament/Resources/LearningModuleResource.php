<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningModuleResource\Pages;
use App\Models\LearningModule;
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

class LearningModuleResource extends Resource
{
    protected static ?string $model = LearningModule::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?string $navigationLabel = 'Modul E-Book';
    protected static ?int $navigationSort = 4;

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
                        Forms\Components\Section::make('Detail Dokumen')
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Pilih Kategori')
                                    ->options(Category::query()->where('is_active', true)->pluck('name', 'id'))
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('topic_id', null))
                                    ->dehydrated(false),

                                Forms\Components\Select::make('topic_id')
                                    ->label('Topik Modul')
                                    ->options(fn (Get $get): Collection => Topic::query()
                                        ->where('category_id', $get('category_id'))
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->relationship('topic', 'name'),

                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Modul / Buku')
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

                                Forms\Components\RichEditor::make('description')
                                    ->label('Ringkasan Isi')
                                    ->toolbarButtons(['bold', 'italic', 'bulletList'])
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Section::make('File Dokumen')
                            ->schema([
                                Forms\Components\FileUpload::make('file_path')
                                    ->label('Upload File PDF')
                                    ->disk('public') // <--- WAJIB: Paksa simpan di Public Disk
                                    ->directory('modules/files')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(20480) // 20MB Max
                                    ->required()
                                    ->downloadable()
                                    ->openable()
                                    ->columnSpanFull()
                                    ->helperText('Format wajib PDF. Maksimal ukuran 20MB.'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Cover & Status')
                            ->schema([
                                Forms\Components\FileUpload::make('cover_image')
                                    ->label('Cover Buku')
                                    ->image()
                                    ->disk('public') // <--- WAJIB: Paksa simpan di Public Disk
                                    ->directory('modules/covers')
                                    ->imageEditor(),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Rekomendasi?')
                                    ->onColor('success'),
                                
                                Forms\Components\Hidden::make('file_type')->default('pdf'),
                                Forms\Components\Hidden::make('file_size')->default(0),
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
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public') // <--- WAJIB
                    ->height(80)
                    ->defaultImageUrl(url('/images/placeholder-book.png')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Modul')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topik')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('topic_id')
                    ->relationship('topic', 'name')
                    ->label('Filter Topik'),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (LearningModule $record) => \Illuminate\Support\Facades\Storage::url($record->file_path))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListLearningModules::route('/'),
            'create' => Pages\CreateLearningModule::route('/create'),
            'edit' => Pages\EditLearningModule::route('/{record}/edit'),
        ];
    }
}