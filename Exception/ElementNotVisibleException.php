<?php
namespace A5sys\MinkContext\Exception;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Session;
use Behat\Mink\Exception\ExpectationException;

/**
 * Exception thrown when an expected element is not visible.
 *
 * @author Arnaud Goulpeau <agoulpeau@a5sys.com>
 */
class ElementNotVisibleException extends ExpectationException
{
    /**
     * Initializes exception.
     *
     * @param DriverInterface|Session $driver   driver instance
     * @param string                  $type     element type
     * @param string                  $selector element selector type
     * @param string                  $locator  element locator
     */
    public function __construct($driver, $type = null, $selector = null, $locator = null)
    {
        $message = '';

        if (null !== $type) {
            $message .= ucfirst($type);
        } else {
            $message .= 'Tag';
        }

        if (null !== $locator) {
            if (null === $selector || in_array($selector, array('css', 'xpath'))) {
                $selector = 'matching '.($selector ?: 'locator');
            } else {
                $selector = 'with '.$selector;
            }
            $message .= ' '.$selector.' "'.$locator.'"';
        }

        $message .= ' not visible.';

        parent::__construct($message, $driver);
    }
}
