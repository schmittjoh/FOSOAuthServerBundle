<?php

namespace FOS\OAuthServerBundle\Util;

/**
 * Message Manager Interface.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface MessageManagerInterface
{
    /**
     * Adds an error message to display to the user.
     *
     * @param string $msg
     * @param Boolean $persist
     */
    function addError($msg, $persist = true);
}