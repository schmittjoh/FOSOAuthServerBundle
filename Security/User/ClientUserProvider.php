<?php

namespace FOS\OAuthServerBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use FOS\OAuth2\Model\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\OAuth2\Storage\ClientStorageInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("fos_oauth_server.client_provider")
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ClientUserProvider implements UserProviderInterface
{
    private $storage;
    private $class;

    public function __construct(ClientStorageInterface $storage, $class)
    {
        $this->storage = $storage;
        $this->class = $class;
    }

    public function loadUserByUsername($clientId)
    {
        if (null === $client = $this->storage->findClientById($clientId)) {
            throw new UsernameNotFoundException('The username does not exist.');
        }

        return $client;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof ClientInterface) {
            throw new \InvalidArgumentException(sprintf('The user must be an instance of ClientInterface, but got "%s".', get_class($user)));
        }

        return $this->loadUserByUsername($user->getIdentifier());
    }

    public function supportsClass($class)
    {
        return $this->class === $class;
    }
}