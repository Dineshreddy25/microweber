<?php

namespace MicroweberPackages\Modules\PDF\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\PDF\Http\Livewire\PDFSettingsComponent;

class PDFServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-pdf');
        $package->hasViews('microweber-module-pdf');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-pdf::settings', PDFSettingsComponent::class);

    }

}
