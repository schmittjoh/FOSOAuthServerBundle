<?php

namespace FOS\FOSOAuthServerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use FOS\FOSOAuthServerBundle\Form\Model\AccessRequest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AuthorizationServerController
{
    /** @DI\Inject */
    private $request;

    /** @DI\Inject */
    private $templating;

    /** @DI\Inject("fos_oauth_server.oauth2.authorization_server") */
    private $authorizationServer;

    /** @DI\Inject("form.csrf_provider") */
    private $csrfProvider;

    /** @DI\Inject("fos_oauth_server.util.message_manager") */
    private $messageManager;

    /** @DI\Inject */
    private $translator;

    /**
     * @PreAuthorize("isAuthenticated()")
     * @Template
     */
    public function authorizationRequestAction()
    {
        $authorizationRequest = $this->authorizationServer->createAuthorizationRequestFromRequest($this->request);

        if ($this->request->query->has('grant') || $this->request->query->has('deny')) {
            if (!$this->csrfProvider->isCsrfTokenValid('authorizationRequest', $this->request->query->get('csrf_token'))) {
                $this->messageManager->addError(
                	/** @Desc("Your session might have timed out, or your browser might not support cookies. Please try again.") */
                    $this->translator->trans('error.invalid_csrf_token', array(), 'FOSOAuthServerBundle'), false);
            } else {
                if ($this->request->query->has('grant')) {
                    return $this->authorizationServer->createSuccessfulResponseForAuthRequest($authorizationRequest);
                }

                return $this->authorizationServer->createUnsuccessfulResponseForAuthRequest($authorizationRequest, 'access_denied');
            }
        }

        return array(
            'authorizationRequest' => $authorizationRequest,
            'csrfToken' => $this->csrfProvider->generateCsrfToken('authorizationRequest'),
        );
    }

    /**
     * @PreAuthorize("isFullyAuthenticated()")
     */
    public function accessTokenAction()
    {
        $accessToken = $this->authorizationServer->createAccessTokenFromRequest($this->request);

        return $this->authorizationServer->createBearerAccessTokenResponse($accessToken);
    }
}