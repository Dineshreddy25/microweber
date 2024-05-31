<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsAdvanced extends SettingsPageDefault
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-advanced';

    protected static string $description = 'Configure your advanced settings';

    protected static ?string $title = 'Advanced';

}
