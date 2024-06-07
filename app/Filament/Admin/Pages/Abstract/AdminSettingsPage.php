<?php

namespace App\Filament\Admin\Pages\Abstract;

use App\Filament\Admin\Pages\Concerns\HasModuleOption;
use App\Filament\Admin\Pages\Concerns\HasOptions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;

abstract class AdminSettingsPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $description = '';

    public function getDescription(): string
    {
        return static::$description;
    }

    public array $options;
    public array $translatableOptions;

    public array $optionGroups = [
        'website'
    ];

    public function getOptionGroups()
    {
        return $this->optionGroups;
    }

    public function mount()
    {
        $getOptions = Option::whereIn('option_group', $this->getOptionGroups())->get();

        if ($getOptions) {
            foreach ($getOptions as $option) {
                $this->options[$option->option_group][$option->option_key] = $option->option_value;
            }
        }

        $getTranslatableOptions = ModuleOption::whereIn('option_group', $this->getOptionGroups())->get();
        if ($getTranslatableOptions) {
            foreach ($getTranslatableOptions as $option) {
                if (!empty($option->multilanguage_translatons)) {
                    foreach ($option->multilanguage_translatons as $translationLocale => $translationField) {
                        $this->translatableOptions[$option->option_group][$option->option_key][$translationLocale] = $translationField['option_value'];
                    }
                }
            }
        }

        return [];
    }



    public function updated($propertyName, $value)
    {
        $changedField = array_undot_str($propertyName);
        if (isset($changedField['options'])) {
            foreach ($changedField['options'] as $optionGroup => $optionKey) {

                save_option([
                    'option_key' => $optionKey,
                    'option_value' => $value,
                    'option_group' => $optionGroup,
                    'module' => 'settings/group/website'
                ]);

                //get the minute of the hour and add it to the notification id to make it unique
                $notificationId = 'settings_updated' . crc32(date('i') . $optionKey.$optionGroup);

                Notification::make($notificationId)
                    ->title('Settings Updated')
                    ->success()
                    ->send();
            }
        }
        if (isset($changedField['translatableOptions'])) {
            foreach ($changedField['translatableOptions'] as $optionGroup => $optionValueLanguages) {
                foreach ($optionValueLanguages as $optionKey=>$optionValueLang) {
                    save_option([
                        'optionValueLanguages' => $optionValueLanguages,
                        'option_key' => $optionKey,
                        'option_value' => $value,
                        'option_group' => $optionGroup,
                        'lang' => $optionValueLang,
                        'module' => 'settings/group/website'
                    ]);

                    Notification::make()
                        ->title('Settings Updated')
                        ->success()
                        ->send();
                }
            }
        }

    }




}
