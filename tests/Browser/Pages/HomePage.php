<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HomePage extends Page
{
    public function url(): string
    {
        return '/';
    }

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assertHomePage()
    {
        $this->browser->waitForLink('Читать Палийский канон');

        return $this;
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }
}
