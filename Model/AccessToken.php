<?php

namespace FOS\OAuthServerBundle\Model;

use FOS\OAuth2\Model\AccessToken as BaseAccessToken;

class AccessToken extends BaseAccessToken
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}