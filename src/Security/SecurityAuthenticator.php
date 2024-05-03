<?php

namespace App\Security;

use App\Entity\Utilisateurs;
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
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class SecurityAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
    private $router;
    private $security;
    private $urlGenerator;

    public const LOGIN_ROUTE = 'app_login';
    private $userRepository;
    private $session;

    public function __construct(UrlGeneratorInterface $urlGenerator, RouterInterface $router, Security $security, UtilisateursRepository $userRepository, SessionInterface $session)
    {
        $this->urlGenerator = $urlGenerator;
        $this->router = $router;
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        $user = $this->userRepository->findOneBy(['email' => $email]);
        
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid email .');
        }
    
        // Check if the user is active
        if (!$user->isEstactif()) {
            //$this->session->getFlashBag()->add('error', ' inactive account.');
            throw new CustomUserMessageAuthenticationException('Inactive account.');
        }
    
        // ReCAPTCHA verification
        $recaptchaResponse = $request->request->get('g-recaptcha-response');
        if (!$recaptchaResponse) {
            throw new CustomUserMessageAuthenticationException('Please check the reCAPTCHA box.');
        }

        // You may need to adjust the reCAPTCHA site key and secret key accordingly
        $recaptcha = new ReCaptcha('
        6Le3PsspAAAAAGgGWlf696-aGw8P8vHZSjqA2u0i');
        $recaptchaResult = $recaptcha->verify($recaptchaResponse, $request->getClientIp());
        if (!$recaptchaResult->isSuccess()) {
            throw new CustomUserMessageAuthenticationException('reCAPTCHA validation failed. Please try again.');
        }

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
        $user = $token->getUser();
    
        // Check the roles of the user
        $roles = $user->getRoles();
    
        if (in_array('admin', $roles)) {
            // Redirect to the admin dashboard
            $targetUrl = $this->router->generate('app_dashadmin');
        } elseif (in_array('user', $roles)) {
            // Redirect to the blog homepage for regular users
            $targetUrl = $this->router->generate('app_client_home');
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
