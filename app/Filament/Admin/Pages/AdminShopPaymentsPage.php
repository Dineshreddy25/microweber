<?php

namespace App\Filament\Admin\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopPaymentsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-payments';

    protected static string $view = 'filament.admin.pages.settings-shop-payments';

    protected static ?string $title = 'Payments';

    protected static string $description = 'Configure your shop payments settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
