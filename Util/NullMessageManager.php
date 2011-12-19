<?php

namespace FOS\OAuthServerBundle\Util;

/**
 * Null Message Manager Implementation.
 *
 * Does nothing, just avoids sprinkling the code with ifs.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class NullMessageManager implements MessageManagerInterface
{
    public function addError($msg, $persist = true) { }
}