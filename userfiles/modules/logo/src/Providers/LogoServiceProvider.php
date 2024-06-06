<?php

namespace MicroweberPackages\Modules\Logo\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class LogoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-logo');
        $package->hasViews('microweber-module-logo');
    }


    public function register(): void
    {
        parent::register();

//        Livewire::component('microweber-module-logo::settings', LogoSettingsComponent::class);
//        ModuleAdmin::registerSettings('logo', 'microweber-module-logo::settings');
         ModuleAdmin::registerLiveEditSettingsUrl('logo', site_url('admin-live-edit/logo-settings'));
     //    ModuleAdmin::registerPanelPage(\MicroweberPackages\Modules\Logo\Http\Livewire\LogoSettings::class);
         ModuleAdmin::registerLiveEditPanelPage(\MicroweberPackages\Modules\Logo\Http\Livewire\LogoSettings::class);

    }
}
