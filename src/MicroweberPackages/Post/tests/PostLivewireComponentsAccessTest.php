<?php

namespace MicroweberPackages\Post\tests;


use MicroweberPackages\Post\Http\Livewire\Admin\PostsList;
use MicroweberPackages\Notification\tests\UserLivewireComponentsAccessTest;

class PostLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        PostsList::class,
    ];
}
