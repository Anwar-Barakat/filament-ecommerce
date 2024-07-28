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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Ecommerce\Filament\Resources\ProductResource\Pages;
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
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Product Info')->schema([
                        Forms\Components\TextInput::make('title')
                            ->autofocus()
                            ->live(onBlur: true)
                            ->unique()
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

                        Forms\Components\MarkdownEditor::make('description')->columnSpan(2),
                    ])->columns(2),

                    Forms\Components\Section::make('Pricing & Inventory')->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU (Stock Keeping Unit)')
                            ->placeholder('Enter product SKU')
                            ->unique()
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
                ]),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Status')->schema([
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

                    Forms\Components\Section::make('Attachments')->schema([
                        Forms\Components\FileUpload::make('image')
                            ->directory('form-attachments')
                            ->preserveFilenames()
                            ->image()
                            ->imageEditor()
                            ->required(),
                    ])->collapsible(),

                    Forms\Components\Section::make('Associations')->schema([
                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'title')
                            ->label('Brand')
                            ->required(),
                    ]),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),

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
