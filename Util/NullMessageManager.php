<?php

namespace FOS\OAuthServerBundle\Util;

class NullMessageManager implements MessageManagerInterface
{
    public function addError($msg, $persist = true) { }
}