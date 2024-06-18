<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use MicroweberPackages\CustomField\Fields\Checkbox;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Marketplace\Models\MarketplaceItem;
use MicroweberPackages\Product\Models\Product;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class ProductResource extends Resource
{
    use Translatable;

    protected static ?string $model = Product::class;

//    protected static ?string $navigationIcon = 'mw-shop';

    protected static ?string $navigationGroup = 'Shop';

    public static function seoForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Search engine optimisation (SEO)')
                            ->description('Add a title and description to see how this product might appear in a search engine listing')
                            ->schema([


                        Forms\Components\TextInput::make('content_meta_title')
                            ->label('Meta Title')
                            ->helperText('Describe for what is this page about in short title')
                            ->columnSpanFull(),


                        Forms\Components\Textarea::make('description')
                            ->label('Meta Description')
                            ->helperText('Please provide a brief summary of this web page')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('content_meta_keywords')
                            ->label('Meta Keywords')
                            ->helperText('Separate keywords with a comma and space. Type keywords that describe your content - Example: Blog, Online News, Phones for sale')
                            ->columnSpanFull(),

            ])
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

//                Forms\Components\Tabs::make('Tabs')
//                    ->tabs([
//
//                        Forms\Components\Tabs\Tab::make('Details')
//                            ->schema([
//
  Forms\Components\Group::make([

                                Forms\Components\Group::make()
                                    ->schema([

                                        Forms\Components\Section::make('General Information')
                                            ->heading(false)
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->columnSpanFull()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                                        if ($operation !== 'create') {
                                                            return;
                                                        }

                                                        $set('url', Str::slug($state));
                                                    }),

                                                Forms\Components\TextInput::make('url')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->columnSpanFull()
                                                    ->unique(Product::class, 'url', ignoreRecord: true),

                                                Forms\Components\MarkdownEditor::make('description')
                                                    ->columnSpan('full'),
                                            ])
                                            ->columnSpanFull()
                                            ->columns(2),


                                        Forms\Components\Section::make('Pricing')
                                            ->schema([

                                                Forms\Components\TextInput::make('price')
                                                    ->numeric()
                                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                                    ->required(),

                                            ])->columnSpanFull(),


                                        Forms\Components\Section::make('Inventory')
                                            ->schema([


                                                Forms\Components\TextInput::make('content_data.sku')
                                                     ->helperText('Stock Keeping Unit'),

                                                Forms\Components\TextInput::make('content_data.barcode')
                                                     ->helperText('ISBN, UPC, GTIN, etc.'),

                                                Forms\Components\Toggle::make('content_data.track_quantity')
                                                    ->label('Track Quantity')
                                                    ->live()
                                                    ->default(false),


                                                Forms\Components\Group::make([
                                                    Forms\Components\TextInput::make('content_data.quantity')
                                                        ->numeric()
                                                        ->rules(['regex:/^\d{1,6}$/'])
                                                        ->default(0),

                                                    Forms\Components\Checkbox::make('content_data.sell_oos')
                                                        ->label('Continue selling when out of stock')
                                                        ->default(false),

                                                    Forms\Components\TextInput::make('content_data.max_qty_per_order')
                                                        ->numeric()
                                                        ->rules(['regex:/^\d{1,6}$/'])
                                                        ->label('Max quantity per order')
                                                        ->default(0),
                                                ])->hidden(function(Forms\Get $get) {
                                                    return !$get('content_data.track_quantity');
                                                }),



                                            ])->columnSpanFull(),

                                        Forms\Components\Section::make('Shipping')
                                            ->schema([

                                                // This is a physical product
                                                Forms\Components\Toggle::make('content_data.physical_product')
                                                    ->label('This is a physical product')
                                                    ->default(true)
                                                    ->live(),

                                                Forms\Components\Group::make([
                                                    Forms\Components\TextInput::make('content_data.shipping_fixed_cost')
                                                        ->numeric()
                                                        ->helperText('Used to set your shipping price at checkout and label prices during fulfillment.')
                                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                                        ->suffix(currency_symbol())
                                                        ->label('Fixed cost')
                                                        ->columnSpanFull()
                                                        ->default(0),

//                                                    Forms\Components\TextInput::make('content_data.weight')
//                                                        ->numeric()
//                                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
//                                                        ->helperText('Used to calculate shipping rates at checkout and label prices during fulfillment.')
//                                                        ->label('Weight')
//                                                        ->default(0),

                                                    Forms\Components\Toggle::make('content_data.free_shipping')
                                                        ->columnSpanFull(),

                                                    Forms\Components\Toggle::make('content_data.shipping_advanced_settings')
                                                        ->label('Show advanced weight settings')
                                                        ->live()
                                                        ->columnSpanFull(),

                                                ])->columns(2)->hidden(function(Forms\Get $get) {
                                                    return !$get('content_data.physical_product');
                                                }),


                                                Forms\Components\Section::make('Shipping Advanced')
                                                    ->heading('Advanced')
                                                    ->description('Advanced product shipping settings.')
                                                    ->schema([
                                                    Forms\Components\TextInput::make('content_data.weight')
                                                        ->numeric()
                                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                                        ->label('Weight (kg)')
                                                        ->default(0),


                                                    Forms\Components\TextInput::make('content_data.width')
                                                        ->numeric()
                                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                                        ->label('Width (cm)')
                                                        ->default(0),

                                                    Forms\Components\TextInput::make('content_data.length')
                                                        ->numeric()
                                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                                        ->label('Length (cm)')
                                                        ->default(0),

                                                    Forms\Components\TextInput::make('content_data.depth')
                                                        ->numeric()
                                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                                        ->label('Depth (cm)')
                                                        ->default(0),

                                                    Forms\Components\Checkbox::make('content_data.params_in_checkout')
                                                        ->label('Show parameters in checkout page')
                                                        ->columnSpanFull()
                                                        ->default(false),

                                                ])
                                                    ->columns(4)
                                                    ->hidden(function(Forms\Get $get) {
                                                    return !$get('content_data.shipping_advanced_settings');
                                                }),

                                            ])->columnSpanFull(),

                                    ])->columnSpan(['lg' => 2]),


                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Section::make('Visible')
                                            ->schema([
                                                Forms\Components\ToggleButtons::make('is_published')
                                                    ->label(false)
                                                    ->options([
                                                        1 => 'Published',
                                                        0 => 'Unpublished',
                                                    ])
                                                    ->default(true),

                                            ]),
                                        Forms\Components\Section::make('Category')
                                            ->schema([

                                            ]),

                                        Forms\Components\Section::make('Tags')
                                            ->schema([
                                                Forms\Components\TagsInput::make('tags')
                                                    ->label(false)
                                                    ->helperText('Separate using commas or Enter key.')
                                                    ->placeholder('Add a tag'),
                                            ]),

                                    ])->columnSpan(['lg' => 1]),



                            ])->columns(3)->columnSpanFull(),

//                            ])->columns(3),


//                Forms\Components\Tabs\Tab::make('Custom Fields')
//                    ->schema([
//                        Forms\Components\Section::make('Custom Fields')
//                            ->heading(false)
//                        ->schema([
//                            Forms\Components\View::make('custom_field::livewire.filament.admin.show-list-custom-fields')
//                                ->columnSpanFull(),
//                        ]),
//                    ]),
//                Forms\Components\Tabs\Tab::make('SEO')
//                    ->schema([
//
//
//
//                        ]),
//
//                    ]),
//                Forms\Components\Tabs\Tab::make('Advanced')
//                    ->schema([
//
//                    ]),
//                    ])->columnSpanFull(),

            ]);
    }


    public static function getListTableColumns(): array
    {

        return [
            ImageUrlColumn::make('media_url')
                ->height(83)
                ->imageUrl(function (Product $product) {
                    return $product->mediaUrl();
                }),

            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->columnSpanFull()
                ->weight(FontWeight::Bold),

            Tables\Columns\TextColumn::make('price_display')
                ->searchable()
                ->columnSpanFull(),

            Tables\Columns\SelectColumn::make('is_active')
                ->options([
                    1 => 'Published',
                    0 => 'Unpublished',
                ]),

        ];
    }

    public static function getGridTableColumns(): array
    {
        return [
            Tables\Columns\Layout\Split::make([

                ImageUrlColumn::make('media_url')
                    ->height(83)
                    ->imageUrl(function (Product $product) {
                        return $product->mediaUrl();
                    }),


                Tables\Columns\Layout\Stack::make([

                    Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->columnSpanFull()
                        ->weight(FontWeight::Bold),

                    Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->columnSpanFull(),

                    Tables\Columns\TextColumn::make('created_at')
                        ->searchable()
                        ->columnSpanFull(),

                ]),

                Tables\Columns\TextColumn::make('price_display')
                    ->searchable()
                    ->columnSpanFull(),

                Tables\Columns\SelectColumn::make('is_active')
                    ->options([
                        1 => 'Published',
                        0 => 'Unpublished',
                    ]),


                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->columnSpanFull(),

            ])
        ];
    }

    public static function table(Table $table): Table
    {

        $livewire = $table->getLivewire();

        return $table
            ->deferLoading()
            ->reorderable('position')
            ->columns(
                $livewire->isGridLayout()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->contentGrid(
                fn() => $livewire->isListLayout()
                    ? null
                    : [
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ]
            )
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('qty')
                            ->label('Quantity')
                            ->relationship('metaData', 'sku'),

                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('sku')
                        ->relationship('metaData', 'sku'),

                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('barcode')
                            ->relationship('metaData', 'barcode'),
                    ]),
            ])
            ->filtersFormWidth(MaxWidth::Medium)
            ->actions([
                //Tables\Actions\EditAction::make(),
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
//            RelationManagers\CustomFieldsRelationManager::class
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
