<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

class FrontendSwitchLanguage extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    public function switchLanguage(Browser $browser, $locale)
    {

        $browser->switchFrameDefault();
        $browser->click('#mw-page-set-preview-mode');

        $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
        $browser->switchFrame($iframeElement);




        //
        if (!$browser->element('#switch_language_ul')) {
            if ($browser->element('#header-layout')) {
                // must enable multilang in header-layout first

              //  $browser->script('location.reload();');

            }
        }


        $browser->pause(300);
        $browser->click('.module-multilanguage > .mw-dropdown-default');
        $browser->pause(400);
        $browser->script('$(\'li[data-value="'.$locale.'"]\', ".module-multilanguage").click()');
     //   $browser->pause(20000);
         $browser->waitForReload(false, 30);



        $browser->switchFrameDefault();
        $browser->click('#mw-page-set-back-to-edit-mode');

        $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
        $browser->switchFrame($iframeElement);



    }
}
