<?php

/*
 * This file is part of the FOSOAuthServerBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\OAuthServerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FOSOAuthServerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration($container->get('kernel.debug'));
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('oauth2.xml');
        $this->configureOAuth2($config['oauth2'], $container);

        if ('doctrine' === $config['db_driver']) {
            $loader->load('doctrine.xml');
            $this->configureDoctrine($config['doctrine'], $container);
        }
    }

    public function getAlias()
    {
        return 'fos_oauth_server';
    }

    private function configureOAuth2(array $config, ContainerBuilder $container)
    {
        $configDef = $container->getDefinition('fos_oauth_server.oauth2.configuration')
            ->addArgument($config['access_ranges'])
            ->addArgument($config['authorization_code_lifetime'])
            ->addArgument($config['access_denied_lifetime'])
        ;
    }

    private function configureDoctrine(array $config, ContainerBuilder $container)
    {
        foreach ($config['classes'] as $key => $class) {
            $container->setParameter('fos_oauth_server.model.'.$key.'.class', $class);
        }
    }
}
