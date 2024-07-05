<?php

namespace Modules\Ecommerce\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Brand;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Ecommerce\Filament\Resources\BrandResource\Pages;
use Modules\Ecommerce\Filament\Resources\BrandResource\RelationManagers;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Brand Info')->schema([
                        Forms\Components\TextInput::make('name')
                            ->autofocus()
                            ->live(onBlur: true)
                            ->unique()
                            ->placeholder('Enter product name')
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
                            ->unique(Brand::class, 'slug', ignoreRecord: true),

                        Forms\Components\TextInput::make('url')
                            ->label('Website URL')
                            ->placeholder('Enter brand website URL')
                            ->url()
                            ->required()
                            ->unique(Brand::class, 'slug', ignoreRecord: true)
                            ->columnSpan('full'),

                        Forms\Components\MarkdownEditor::make('description')->columnSpan(2),
                    ])->columns(2),
                ]),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Brand Appearance')->schema([
                        Forms\Components\Toggle::make('is_visible')
                            ->label('Visible')
                            ->helperText('Whether the brand is visible on the front-end')
                            ->default(true),
                    ])->columns(2),

                    Forms\Components\Section::make('Brand Colors')->schema([
                        Forms\Components\ColorPicker::make('primary_hex')
                            ->label('Primary Color')
                            ->default('#000000')
                            ->required()
                            ->columnSpan('full'),
                    ])->columns(2),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('Website URL')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ColorColumn::make('primary_hex')
                    ->label('Color')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->date()
                    ->searchable()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
