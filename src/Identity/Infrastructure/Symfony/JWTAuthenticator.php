<?php

namespace Przper\Tribe\Identity\Infrastructure\Symfony;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class JWTAuthenticator extends \Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($exception instanceof ExpiredTokenException) {
            $response = new RedirectResponse('/login');
            $response->headers->clearCookie('BEARER');
            return $response;
        }

        return parent::onAuthenticationFailure($request, $exception);
    }
}