<?php

namespace MicroweberPackages\Modules\Teamcard\Models;

use MicroweberPackages\LiveEdit\Models\ModuleItemSushi;

class TeamcardItem extends ModuleItemSushi
{
    protected $fillable = [
        'id',
        'name',
        'file',
        'bio',
        'website',
        'position',
    ];
    protected array $schema = [
        'id' => 'string',
        'name' => 'string',
        'file' => 'string',
        'bio' => 'string',
        'website' => 'string',
        'position' => 'integer',
    ];
}
