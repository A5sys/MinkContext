<?php

namespace A5sys\MinkContext\Traits;

use Behat\Mink\Exception\ElementNotFoundException;

/**
 *
 * @author Thomas BEAUJEAN
 */
trait IonicTrait
{
    /**
     * Wait for the page to be loaded by ionic
     *
     * @param string $page
     *
     * @Then /^(?:|The )ionic page "(?P<page>(?:[^"]|\\")*)" is loaded$/
     */
    public function isPageLoaded($page)
    {
        $session = $this->getSession();

        //a hidden one has no offsetWidth
        $session->wait($this->timeout, "document.getElementById('".$page."') && document.getElementById('".$page."').offsetWidth !== 0");

        $pageEl = $this->getSession()->getPage();
        $locator = $this->fixStepArgument($page);
        $el = $pageEl->findById($locator);

        if (null === $el) {
            throw new ElementNotFoundException($this->getSession(), 'link', 'id|title|alt|text', $locator);
        }
    }
}
