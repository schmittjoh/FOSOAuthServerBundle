<?php

namespace FOS\OAuthServerBundle\Model;

use FOS\OAuth2\Model\Client as BaseClient;

class Client extends BaseClient
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}