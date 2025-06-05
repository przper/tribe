<?php

namespace Przper\Tribe\Identity\Ports\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoginController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire(env: 'AUTH_SERVICE_URL')]
        private string $authenticationServiceUrl,
    ) {
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $response = $this->client->request(
                'POST',
                $this->authenticationServiceUrl . '/api/login_check',
                ['json' => ['username' => $email, 'password' => $password]],
            );

            if ($response->getStatusCode() !== 200) {
                return $this->render('identity/login.html.twig', [
                    'last_username' => $email,
                    'error' => 'Invalid credentials.',
                ]);
            }

            $response = $response->toArray();
            $token = $response['token'];

            $response = $this->redirect('/');

            $response->headers->setCookie(Cookie::create('BEARER', $token)->withHttpOnly());

            return $response;
        }

        return $this->render('identity/login.html.twig');
    }
}