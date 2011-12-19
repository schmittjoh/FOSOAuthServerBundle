<?php

namespace FOS\OAuthServerBundle\Model;

use FOS\OAuth2\Model\AuthorizationCode as BaseAuthorizationCode;

class AuthorizationCode extends BaseAuthorizationCode
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}