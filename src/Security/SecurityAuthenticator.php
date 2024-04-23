<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\RouterInterface;

class SecurityAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
    private $router;
    private $security;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator,RouterInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
       /* if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);*/
       /* if ($this->security->isGranted('admin')) {
            // Redirect to the admin dashboard
            $targetUrl = $this->router->generate('app_dashboard');
        } else {
            // Redirect to the blog homepage for regular users
            $targetUrl = $this->router->generate('app_articles');
        }

        return new RedirectResponse($targetUrl);*/
        $user = $token->getUser();

    
        
            // Check the roles of the user
            $roles = $user->getRoles();
        
            if (in_array('admin', $roles)) {
                // Redirect to the admin dashboard
                $targetUrl = $this->router->generate('app_dashboard');
            } elseif (in_array('user', $roles)) {
                // Redirect to the blog homepage for regular users
                $targetUrl = $this->router->generate('app_articles');
            } else {
                // Handle other roles or unauthorized access
                $targetUrl = $this->router->generate('app_login'); // Redirect to login page or handle as per your application logic
            }
        

    return new RedirectResponse($targetUrl);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
