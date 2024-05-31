<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsShopAutoRespondEmail extends SettingsPageDefault
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-auto-respond-email';

    protected static ?string $title = 'Auto Respond Email';

    protected static string $description = 'Configure your shop auto respond email settings';

    protected static ?string $navigationGroup = 'Shop Settings';

}
