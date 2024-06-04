<?php

namespace MicroweberPackages\Filament\Plugins;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Panel;

class FilamentTranslatableFieldsPlugin implements Plugin
{
    protected array|Closure $supportedLanguages = [];

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'outerweb-filament-translatable-fields';
    }

    public function supportedLanguages(array|Closure $supportedLanguages): static
    {
        $this->supportedLanguages = $supportedLanguages;

        return $this;
    }

    public function getSupportedLanguages(): array
    {
        $locales = is_callable($this->supportedLanguages) ? call_user_func($this->supportedLanguages) : $this->supportedLanguages;

        return $locales;
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        $supportedLanguages = $this->getSupportedLanguages();

        Field::macro('mwTranslatableOption', function () use ($supportedLanguages) {

            if (empty($supportedLanguages)) {
                return $this;
            }

            $textInput = TextInput::make($this->getName())
                ->live()
                ->debounce(800)
                ->view('filament-forms::components.text-input-option-translatable',[
                    'supportedLanguages' => $supportedLanguages,
                ]);

            return $textInput;
        });

    }
}
