<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PageResource\Pages;
use App\Filament\Admin\Resources\PageResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

 //   protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Website';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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

                        Tables\Columns\TextColumn::make('created_at')
                            ->searchable()
                            ->columnSpanFull(),

                    ]),


                    Tables\Columns\SelectColumn::make('is_active')
                        ->options([
                            1 => 'Published',
                            0 => 'Unpublished',
                        ]),


                    Tables\Columns\TextColumn::make('created_at')
                        ->searchable()
                        ->columnSpanFull(),

                ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
