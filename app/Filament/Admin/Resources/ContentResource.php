<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContentResource\Pages;
use App\Filament\Admin\Resources\ContentResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Component;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\User\Models\User;

class ContentResource extends Resource
{
    use Translatable;

    protected static ?string $model = \MicroweberPackages\Content\Models\Content::class;


    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {

        $livewire = $form->getLivewire();

        $contentType = 'page';
        $contentSubtype = 'static';

        $isShop = false;

        $id = 0;

        if (
            $livewire instanceof \App\Filament\Admin\Resources\PageResource\Pages\CreatePage ||
            $livewire instanceof \App\Filament\Admin\Resources\PageResource\Pages\EditPage
        ) {
            $contentType = 'page';
            $contentSubtype = 'static';
        }

        if (
            $livewire instanceof \App\Filament\Admin\Resources\PostResource\Pages\CreatePost ||
            $livewire instanceof \App\Filament\Admin\Resources\PostResource\Pages\EditPost
        ) {
            $contentType = 'post';
            $contentSubtype = 'post';

        }

        if ($livewire instanceof \App\Filament\Admin\Resources\ProductResource\Pages\CreateProduct ||
            $livewire instanceof \App\Filament\Admin\Resources\ProductResource\Pages\EditProduct) {
            $contentType = 'product';
            $contentSubtype = 'product';
        }

        $record = $form->getRecord();
        if ($record) {
            $id = $record->id;
        }
        $templates = site_templates();
        $active_site_template = template_name();



        $parent = 0;
        if($record){
            $parent = $record->parent;
        }





//        $layout_options['site_template'] = $active_site_template;
//        $layout_options['no_cache'] = true;
//        $layout_options['no_folder_sort'] = true;
//
//        $layouts = mw()->layouts_manager->get_all($layout_options);


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

                            Forms\Components\TextInput::make('id')
                                ->default($id)
                                ->hidden(),


                            Forms\Components\TextInput::make('content_type')
                                ->default($contentType)
                                ->hidden(),


                            Forms\Components\TextInput::make('subtype')
                                ->default($contentSubtype)
                                ->hidden(),


                            Forms\Components\TextInput::make('parent')
                                ->default($parent)
                                ->hidden(),


                            Forms\Components\TextInput::make('is_shop')
                                ->default($contentSubtype)
                                ->hidden(),


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
                                        //     ->disabled()
                                        ->dehydrated()
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull()
                                        ->unique(Content::class, 'url', ignoreRecord: true),

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

                                ])->columnSpanFull()->visible(function (Forms\Get $get) {
                                    return $get('content_type') == 'product';
                                }),


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
                                    ])->hidden(function (Forms\Get $get) {
                                        return !$get('content_data.track_quantity');
                                    }),


                                ])->columnSpanFull()->visible(function (Forms\Get $get) {
                                    return $get('content_type') == 'product';
                                }),


                            Forms\Components\Section::make('Select Template')
                                ->schema([
                                    Forms\Components\Select::make('active_site_template')
                                        ->label('Template')
                                        ->default($active_site_template)
                                        ->live()
                                        ->reactive()
                                        ->options(function (Forms\Get $get, Forms\Set $set) use ($templates) {
                                            return collect($templates)->mapWithKeys(function ($template) {
                                                return [$template['dir_name'] => $template['name']];
                                            });
                                        })
                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state, Component $livewire) {





                                        })
                                        ->afterStateUpdated(fn(Forms\Components\Select $component) => $component
                                            ->getContainer()
                                            ->getComponent('dynamicSelectLayout')
                                            ->getChildComponentContainer()
                                            ->fill())

                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state, Component $livewire) {

                                            $data = $livewire->data;

                                            $layout_options = array();

                                            $active_site_template = isset($data['active_site_template']) ? $data['active_site_template'] : template_name();
                                            $layout_options['active_site_template'] = $active_site_template;

                                            $layout_file = isset($data['layout_file']) ? $data['layout_file'] : 'clean.php';


                                            $layout_options['layout_file'] = $layout_file;
                                            $layout_options['no_cache'] = true;
                                            $layout_options['no_folder_sort'] = true;

                                            $layout = mw()->layouts_manager->get_layout_details($layout_options);
                                            $url = '';


                                            if (isset($layout['layout_file_preview_url'])) {
                                                $url = $layout['layout_file_preview_url'];
                                            }


                                            $livewire->dispatch('dynamicPreviewLayoutChange', data: $data, iframePreviewUrl: $url);




                                        })
                                        ->columnSpanFull(),


                                    Forms\Components\Select::make('layout_file')
                                        ->label('Layout')
                                        ->live()
                                        ->reactive()
                                        ->options(function (Forms\Get $get, Forms\Set $set) {
                                            $active_site_template = $get('active_site_template');

                                            $layout_options = [];
                                            $layout_options['site_template'] = $active_site_template;
                                            $layout_options['no_cache'] = true;
                                            $layout_options['no_folder_sort'] = true;

                                            $layouts = mw()->layouts_manager->get_all($layout_options);


                                            return collect($layouts)->mapWithKeys(function ($layout) {
                                                return [$layout['layout_file'] => $layout['name']];
                                            });

                                        })
                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state, Component $livewire) {

                                            $data = $livewire->data;

                                            $layout_options = array();

                                            $active_site_template = isset($data['active_site_template']) ? $data['active_site_template'] : template_name();
                                            $layout_options['active_site_template'] = $active_site_template;

                                            $layout_file = isset($data['layout_file']) ? $data['layout_file'] : 'clean.php';


                                            $layout_options['layout_file'] = $layout_file;
                                            $layout_options['no_cache'] = true;
                                            $layout_options['no_folder_sort'] = true;

                                            $layout = mw()->layouts_manager->get_layout_details($layout_options);
                                            $url = '';


                                            if (isset($layout['layout_file_preview_url'])) {
                                                $url = $layout['layout_file_preview_url'];
                                            }


                                            $livewire->dispatch('dynamicPreviewLayoutChange', data: $data, iframePreviewUrl: $url);


                                        })
                                        ->key('dynamicSelectLayout')
                                        ->columnSpanFull(),

                                    Forms\Components\View::make('content::admin.content.filament.render-template-preview-iframe')
                                        ->viewData(['url' => ''])
//                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state) {
//                                            dd($get);
//                                        })
                                        ->key('dynamicPreviewLayout')
                                        ->columnSpanFull(),


                                ])->columnSpanFull()->visible(function (Forms\Get $get) {
                                    return $get('content_type') == 'page';
                                }),







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

                                    ])->columns(2)->hidden(function (Forms\Get $get) {
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
                                        ->visible(function (Forms\Get $get) {
                                            return $get('content_data.shipping_advanced_settings');
                                        }),

                                ])->columnSpanFull()->visible(function (Forms\Get $get) {
                                    return $get('content_type') == 'product';
                                }),

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
                            Forms\Components\Section::make('Parent page')
                                ->schema([
                                    Forms\Components\View::make('content::admin.content.filament.edit-record-render-category-tree')
                                        ->viewData(['parent' => $parent, 'content_type' => $contentType, 'subtype' => $contentSubtype])
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


    public static function advancedSettingsForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Advanced Settings')
                    ->description('You can configure advanced settings for this content')
                    ->schema([

                        Forms\Components\TextInput::make('original_link')
                            ->label('Redirect URL')
                            ->helperText('Redirect to another URL when this content is accessed')
                            ->columnSpanFull(),


                        Forms\Components\Toggle::make('require_login')
                            ->label('Require login')
                            ->helperText('Require user to be logged in to view this content')
                            ->columnSpanFull(),


                        Forms\Components\Select::make('created_by')
                            ->label('Author')
                            ->placeholder('Select author')
                            // ->options(User::all()->pluck('email', 'id'))
                            ->getSearchResultsUsing(fn(string $search) => User::where('email', 'like', "%{$search}%")->limit(50)->pluck('email', 'id'))
                            ->getOptionLabelUsing(fn($value): ?string => User::find($value)?->email)
                            ->getSelectedRecordUsing(fn($value) => User::find($value))
                            ->searchable(),


                        //change conten type select
                        Forms\Components\Select::make('content_type')
                            ->label('Content Type')
                            ->options([
                                'page' => 'Page',
                                'post' => 'Post',
                                'product' => 'Product',
                            ]),


                        Forms\Components\Select::make('subtype')
                            ->label('Content Subtype')
                            ->options([
                                'static' => 'Static',
                                'post' => 'Post',
                                'product' => 'Product',
                                'dynamic' => 'Dynamic',
                            ]),


                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Created At')
//                            ->date(function ($record) {
//
//                                return isset($record->updated_at) ? $record->created_at->format('Y-m-d H:i:s') : null;
//                            })
                            //  ->format('Y-m-d H:i:s')
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('updated_at')
                            ->label('Updated At')
                            //                            ->date(function ($record) {
//                                return isset($record->updated_at) ? $record->updated_at : null;
//                            })
                            //     ->format('Y-m-d H:i:s')
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('id')
                            ->label('ID')
                            ->inlineLabel(true)
                            ->content(function ($record) {
                                return $record->id;
                            })->visible(function (Forms\Get $get) {
                                return $get('id');
                            }),


                    ])
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


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'view' => Pages\ViewContent::route('/{record}'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
