<?php

namespace A5sys\MinkContext\Traits;

use Behat\Mink\Exception\ElementNotFoundException;

/**
 *
 * @author Thomas BEAUJEAN
 */
class MinkTrait
{
    //the time to wait for an ajax request
    protected $ajaxTimeout = 10000;

    /**
     * Clicks link with specified id|title|alt|text.
     *
     * @param string $link
     *
     * @When /^(?:|I )click "(?P<link>(?:[^"]|\\")*)"$/
     */
    public function clickLink($link)
    {
        $locator = $this->fixStepArgument($link);
        $page = $this->getSession()->getPage();

        $linkElement = $page->findById($locator);

        if (null === $linkElement) {
            throw $this->elementNotFound('link', 'id|title|alt|text', $locator);
        }

        $link->click();
    }

    /**
     * Press an element
     *
     * @param string $element
     *
     * @When /^(?:|I )press the element "(?P<element>(?:[^"]|\\")*)"$/
     */
    public function iPressAnElement($element)
    {
        $session = $this->getSession();

        //a hidden one has no offsetWidth
        //select with css
        $docSelector = "document.querySelectorAll('".$element."')";
        $query = $docSelector.".length > 0 && ".$docSelector."[0].offsetWidth !== 0";
        $session->wait($this->timeout, $query);

        $el = $this->getElementByCss($element);
        $el->click();
    }

    /**
     * Press an element
     *
     * @param string $element
     *
     * @When /^(?:|I )press the xpath element "(?P<element>(?:[^"]|\\")*)"$/
     */
    public function iPressAnXpathElement($element)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        //a hidden one has no offsetWidth
        //select with css
        $domElement = $page->find('xpath', $element);

        if ($domElement === null) {
            throw $this->elementNotFound('xpath', $element);
        }

        $domElement->click();
    }

    /**
     * Test the visibility of an element
     *
     * @param string $element
     *
     * @Then /^(?:|The )element "(?P<element>(?:[^"]|\\")*)" should be visible$/
     */
    public function isElementVisible($element)
    {
        $session = $this->getSession();

        //a hidden one has no offsetWidth
        //select with css
        $docSelector = "document.querySelectorAll('".$element."')";
        $query = $docSelector.".length > 0 && ".$docSelector."[0].offsetWidth !== 0";
        $session->wait($this->timeout, $query);

        $this->getElementByCss($element);
    }

    /**
     * Test the NOT visibility of an element
     *
     * @param string $element
     *
     * @Then /^(?:|The )element "(?P<element>(?:[^"]|\\")*)" should not be visible$/
     */
    public function isElementNotVisible($element)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        //a hidden one has no offsetWidth
        //select with css
        $docSelector = "document.querySelectorAll('".$element."')";
        $query = $docSelector.".length === 0";
        $session->wait($this->timeout, $query);

        $el = $page->find('css', $element);

        if (null !== $el) {
            throw $this->elementNotFound('element', 'css', $element);
        }
    }

    /**
     * Scroll to the top
     *
     * @When /^Scroll to top$/
     */
    public function scrollToTop()
    {
        $session = $this->getSession();
        $script = 'window.scrollTo(0, 0);';
        $session->executeScript($script);
    }

    /**
     * I wait for all ajax done
     *
     * @When /^(?:|I )wait for ajax to be done$/
     */
    public function iWaitForAjaxDone()
    {
        $this->getSession()->wait($this->ajaxTimeout, '(0 === jQuery.active)');
    }

    /**
     * Get an element by its css path
     *
     * @param string $cssPath
     * @return Element
     */
    protected function getElementByCss($cssPath)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $el = $page->find('css', $cssPath);

        if (null === $el) {
            throw $this->elementNotFound('element', 'css', $cssPath);
        }

        return $el;
    }

    /**
     * Builds an ElementNotFoundException
     *
     * This is an helper to build the ElementNotFoundException without
     * needing to use the deprecated getSession accessor in child classes.
     *
     * @param string      $type
     * @param string|null $selector
     * @param string|null $locator
     *
     * @return ElementNotFoundException
     */
    protected function elementNotFound($type, $selector = null, $locator = null)
    {
        return new ElementNotFoundException($this->getSession(), $type, $selector, $locator);
    }

    /**
     * Set the timeout for ajax waiter
     *
     * @param int $timeout
     */
    protected function setAjaxTimeout($timeout)
    {
        $this->ajaxTimeout = $timeout;
    }

    /**
     *
     * @return int
     */
    protected function getAjaxTimeout()
    {
        return $this->ajaxTimeout;
    }
}