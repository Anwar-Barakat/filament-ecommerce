<?php

namespace Modules\Ecommerce\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Enum\ProductTypeEnum;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Ecommerce\Filament\Resources\ProductResource\Pages;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;
use Modules\Ecommerce\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $recordTitleAttribute = 'title';

    protected static int $globalSearchResultsLimit = 20;

    // protected static ?string $activeNavigationIcon = 'heroicon-o-check-badge';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'brand'     => $record->brand->title,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with('brand');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('post')->tabs([
                    Tab::make('Product Info')->schema([
                        Forms\Components\Hidden::make('user_id')->dehydrateStateUsing(fn ($state) => auth()->id()),
                        Forms\Components\TextInput::make('title')
                            ->autofocus()
                            ->live(onBlur: true)
                            ->unique(Product::class, 'title', ignoreRecord: true)
                            ->placeholder('Enter product title')
                            ->required()
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'title')
                            ->label('Brand')
                            ->required(),

                        SelectTree::make('categories')
                            ->relationship('categories', 'title', 'parent_id')
                            ->required()
                            ->searchable()
                            ->enableBranchNode(),

                        Forms\Components\MarkdownEditor::make('description')->columnSpan(2),
                        Forms\Components\TextInput::make('mete_description')->columnSpan(2),
                    ])->columns(2),
                    Tab::make('Pricing & Inventory')->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU (Stock Keeping Unit)')
                            ->placeholder('Enter product SKU')
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                            ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),

                        Forms\Components\Select::make('type')->options([
                            'downloadable' => ProductTypeEnum::DOWNLOADABLE->value,
                            'deliverable' => ProductTypeEnum::DELIVERABLE->value,
                        ])->required(),
                    ])->columns(2),
                    Tab::make('Variants')->schema([
                        AdjacencyList::make('variants')->form([
                            Forms\Components\TextInput::make('title')
                                ->label('Variant Title')
                                ->placeholder('Enter variant title')
                                ->required(),

                            Forms\Components\TextInput::make('sku')
                                ->label('Variant SKU')
                                ->placeholder('Enter variant SKU')
                                ->required(),

                            Forms\Components\TextInput::make('price')
                                ->label('Variant Price')
                                ->numeric()
                                ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                                ->required(),

                            Forms\Components\TextInput::make('type')
                                ->label('Type')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required(),
                        ])
                            ->label('title')
                        ->columns(4)
                    ])->columns(2),
                    Tab::make('Status')->schema([
                        Forms\Components\Toggle::make('is_visible')
                            ->label('Visibility')
                            ->helperText('Enable or disable product visibility')
                            ->default(true),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->helperText('Enable or disable products featured status'),

                        Forms\Components\DatePicker::make('published_at')
                            ->label('Availability')
                            ->default(now())
                            ->columnSpan(2),
                    ])->columns(2),
                    Tab::make('Media')->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->collection('products')
                            ->rules(['file', 'mimes:jpeg,png', 'max:1024'])
                            ->afterStateUpdated(function ($state, $component) {
                                if ($state) {
                                    Log::info('Image uploaded: ' . $state);
                                }
                            })
                            ->optimize('webp'),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                            ->image()
                            ->imageEditor()
                            ->collection('products_gallery')
                            ->multiple()
                            ->optimize('webp')
                    ])->columns(2),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')->collection('products'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('brand.title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->boolean()
                    ->sortable()
                    ->toggleable()
                    ->label('Visibility'),

                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->sortable()
                    ->toggleable()
                    ->date(),

                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->trueLabel('Visible Products')
                    ->falseLabel('Hidden Products')
                    ->native(false),

                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'title')
                    ->label('Brand')
                    ->options(fn () => \App\Models\Brand::pluck('title', 'id')->toArray()),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

}
