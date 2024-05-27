<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Filament\Providers;


use Arcanedev\Support\Providers\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\Core\Providers\Concerns\MergesConfig;
use Filament\FilamentServiceProvider as BaseFilamentPackageServiceProvider;


class FilamentServiceProvider extends BaseFilamentPackageServiceProvider
{
    use MergesConfig;

    public function packageRegistered(): void
    {
        parent::packageRegistered();
        //register FilamentManager
        $this->mergeConfigFrom(__DIR__ . '/../config/filament.php', 'filament');

        $this->app->scoped('filament', function (): FilamentManager {
            return new FilamentManager();
        });

        $this->app->register(TablesServiceProvider::class);
        $this->app->register(NotificationsServiceProvider::class);
        $this->app->register(FormsServiceProvider::class);
        $this->app->register(WidgetsServiceProvider::class);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();
        $originalFolder = new \ReflectionClass(\Filament\FilamentServiceProvider::class);
        $originalFolder = dirname(dirname(dirname($originalFolder->getFileName())));
        $originalFolder = normalize_path($originalFolder, true);

        if (is_dir($originalFolder)) {
            View::addNamespace('filament', $originalFolder . 'filament/resources/views/components');
            View::addNamespace('filament-panels', $originalFolder . 'filament/resources/views');
            View::addNamespace('filament-forms', $originalFolder . 'forms/resources/views');
            View::addNamespace('filament-notifications', $originalFolder . 'notifications/resources/views');
            View::addNamespace('filament-tables', $originalFolder . 'tables/resources/views');
            View::addNamespace('filament-widgets', $originalFolder . 'widgets/resources/views');

            $this->loadTranslationsFrom($originalFolder . 'filament/resources/lang', 'filament-panels');
            $this->loadTranslationsFrom($originalFolder . 'forms/resources/lang', 'filament-forms');
            $this->loadTranslationsFrom($originalFolder . 'notifications/resources/lang', 'filament-notifications');
            $this->loadTranslationsFrom($originalFolder . 'tables/resources/lang', 'filament-tables');
            $this->loadTranslationsFrom($originalFolder . 'widgets/resources/lang', 'filament-widgets');

        }
        View::prependNamespace('filament-panels', dirname(__DIR__).'/resources/views/filament');
        View::prependNamespace('filament-tables', dirname(__DIR__).'/resources/views/filament-tables');
        View::prependNamespace('filament-infolists', dirname(__DIR__).'/resources/views/filament-infolists');
        //View::prependNamespace('filament-panels', base_path() . '/resources/views');

        Filament::serving(function () {
            \Livewire\Livewire::forceAssetInjection();
            Filament::registerViteTheme(
                'resources/css/microweber-admin-filament.scss',
                'public/build'
            );
            Filament::registerViteTheme(
                'resources/css/filament/admin/theme.css',
                'public/build'
            );
        });
    }





//    use MergesConfig;
//
//    public function register()
//    {
//        $this->app->register(TablesServiceProvider::class);
//        $this->app->register(NotificationsServiceProvider::class);
//        $this->app->register(FormsServiceProvider::class);
//        $this->app->register(FilamentPackageServiceProvider::class);
//
//        $this->mergeConfigFrom(__DIR__ . '/../config/filament.php', 'filament');
//        $this->mergeConfigFrom(__DIR__ . '/../config/notifications.php', 'notifications');
//    }
//
//
//    public function boot()
//    {
//        $originalFolder = new \ReflectionClass(\Filament\FilamentServiceProvider::class);
//        $originalFolder = dirname(dirname($originalFolder->getFileName()));
//        $originalViewsFolder = normalize_path($originalFolder . '/resources/views', true);
//        $originalLangFolder = normalize_path($originalFolder . '/resources/lang', true);
//        if (is_dir($originalViewsFolder)) {
//            //     View::addNamespace('filament', $originalViewsFolder);
//            View::addNamespace('filament-panels', $originalViewsFolder);
//         //   View::addNamespace('filament', $originalViewsFolder.'/components');
//        }
//
//     // View::prependNamespace('filament', dirname(__DIR__).'/resources/views');
//       // View::prependNamespace('filament', base_path() . '/resources/views');
//
//      //  View::prependNamespace('filament-panels', dirname(__DIR__) . '/resources/views');
//     //   View::prependNamespace('filament-panels', base_path() . '/resources/views');
//
//        if (is_dir($originalLangFolder)) {
//            $this->loadTranslationsFrom($originalLangFolder, 'filament-panels');
//        }
//
//        Filament::serving(function () {
//            Filament::registerViteTheme(
//                'resources/css/microweber-admin-filament.scss',
//                'public/build'
//            );
//
//        });
//    }
}
