<?php

namespace Modules\Ecommerce\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Ecommerce\Filament\Resources\CategoryResource\Pages;
use Modules\Ecommerce\Filament\Resources\CategoryResource\RelationManagers;
use Modules\Ecommerce\Filament\Resources\ProductResource\RelationManagers\ProductsRelationManager;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('post')->tabs([
                    Tab::make('Content')->schema([
                        Forms\Components\Group::make()
                            ->schema([

                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        if ($operation !== 'create') {
                                            return;
                                        }
                                        $set('slug', Str::slug($state));
                                    })
                                    ->unique(Category::class, 'title', ignoreRecord:true),


                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(Category::class, 'slug', ignoreRecord:true),

                                Forms\Components\MarkdownEditor::make('description')
                                    ->columnSpanFull()
                            ])->columns(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Visibility')
                                    ->helperText('Enable or disable category visibility')
                                    ->default(true),

                                Forms\Components\Select::make('parent_id')
                                    ->relationship('parentCategory', 'title')
                                    ->label('Parent Category')
                                    ->placeholder('Select a parent category')
                                    ->nullable()
                                    ->options(function (callable $get) {
                                        $categoryId = $get('id');
                                        return Category::query()->where('id', '!=', $categoryId)->pluck('title', 'id');
                                    })
                            ]),
                    ])->columns(2),
                    Tab::make('Media')->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->collection('categories')
                            ->rules(['file', 'mimes:jpeg,png', 'max:1024'])
                            ->afterStateUpdated(function ($state, $component) {
                                if ($state) {
                                    \Log::info('Image uploaded: ' . $state);
                                }
                            })
                            ->optimize('webp'),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                            ->image()
                            ->imageEditor()
                            ->collection('categories_gallery')
                            ->multiple()
                            ->optimize('webp')
                    ])->columns(2)
                ])->columnSpanFull()
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('categories'),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('parentCategory.title')
                    ->label('Parent')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->label('Updated Date')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
