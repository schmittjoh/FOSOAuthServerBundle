<?php

namespace FOS\OAuthServerBundle\EventListener;

use FOS\OAuth2\ExceptionTranslatorInterface;
use FOS\OAuth2\Exception\Exception;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class EventListener
{
    private $exceptionTranslator;

    public function __construct(ExceptionTranslatorInterface $translator)
    {
        $this->exceptionTranslator = $translator;
    }

    /**
     * @DI\Observe("kernel.exception")
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!($ex = $event->getException() instanceof Exception)) {
            return;
        }

        // TODO
    }
}