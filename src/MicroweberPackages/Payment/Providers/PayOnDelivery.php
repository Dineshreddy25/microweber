<?php

namespace MicroweberPackages\Payment\Providers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class PayOnDelivery extends \MicroweberPackages\Payment\PaymentMethod
{

    public function title(): string
    {
        return 'Pay on delivery';
    }

    public function process($data = [])
    {
        return [
            'success' => true,
            // 'redirect' => route('checkout.success')
        ];
    }

    public function getSettingsForm($form): array
    {
        return [
            Forms\Components\Section::make()
                ->statePath('settings')
                ->reactive()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    $provider = $get('provider');

                    return [
                        Forms\Components\Textarea::make('payment_instructions')
                            ->label('Payment Instructions')
                            ->columnSpan('full')
                            ->default('')
                    ];

                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'pay_on_delivery'

                    );
                })
                ->columns(2)
            ,

        ];
    }


    public function view(): string
    {
        return 'payment::pay_on_delivery';
    }

}
