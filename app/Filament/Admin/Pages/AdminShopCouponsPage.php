<?php

namespace App\Filament\Admin\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopCouponsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-coupon';

    protected static string $view = 'filament.admin.pages.settings-shop-coupons';

    protected static ?string $title = 'Coupons';

    protected static string $description = 'Configure your shop coupons settings';

    protected static ?string $navigationGroup = 'Shop Settings';

}
