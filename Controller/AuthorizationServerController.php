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

        if ($this->request->query->has('action')) {
            if (!$this->csrfProvider->isCsrfTokenValid('authorizationRequest', $this->request->query->get('csrf_token'))) {
                $this->messageManager->addError(
                /** @Desc("Your session might have timed out, or your browser might not support cookies. Please try again.") */
                $this->translator->trans('error.invalid_csrf_token', array(), 'FOSOAuthServerBundle'), false);
            } else {
                if ('grant' === $this->request->query->get('action')) {
                    return $this->authorizationServer->createSuccessfulResponseForAuthRequest($request);
                }

                return $this->authorizationServer->createUnsuccessfulResponseForAuthRequest($request, 'access_denied');
            }
        }

        return array(
        	'authorizationRequest' => $authorizationRequest,
        	'csrf_token' => $this->csrfProvider->generateCsrfToken('authorizationRequest'),
        );
    }

    public function accessTokenAction()
    {
        $accessToken = $this->authorizationServer->createAccessTokenFromRequest($this->request);

        $data = array(
            'access_token' => $accessToken->getValue(),
            'token_type'   => 'bearer',
        );

        if ($expiresIn = $accessToken->getExpiresIn()) {
            $data['expires_in'] = $expiresIn;
        }

        $response = new Response(json_encode($data));
        $response->headers->addCacheControlDirective('no-store');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }
}