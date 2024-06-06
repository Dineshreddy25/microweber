<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Vite;

class AdminFilamentJsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $srcipts = Vite::asset('src/MicroweberPackages/LiveEdit/resources/js/ui/admin-filament-app.js');
        $styles = Vite::asset('src/MicroweberPackages/LiveEdit/resources/js/ui/css/admin-filament.scss');
        $append_html = '' . "\r\n";
        $append_html .= '<script src="' . $srcipts . '" type="module" id="mw-filament-js-core-scripts"></script>' . "\r\n";
        $append_html .= '<link rel="stylesheet" href="' . $styles . '" id="mw-filament-js-core-styles">' . "\r\n";

        return $append_html;
    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_FOOTER;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'apijs',
        ];
    }
}
