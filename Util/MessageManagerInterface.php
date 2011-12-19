<?php

namespace FOS\OAuthServerBundle\Util;

interface MessageManagerInterface
{
    function addError($msg, $persist = true);
}