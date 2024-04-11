<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateurs;
use App\Form\UserRegistrationFormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginFormType;

class UserController extends AbstractController
{
  /*  #[Route(path: '/login', name: 'login')]
public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
{
    // This dumps request data; ensure it's showing your expected fields
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    $form = $this->createForm(LoginFormType::class);
    $form->handleRequest($request); // This line is crucial

    return $this->render('signin.html.twig', [
        'last_username' => $lastUsername,
        'error'         => $error,
        'loginForm' => $form->createView(),
    ]);

}

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }*/

    #[Route('/register', name: 'user_registration')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Utilisateurs();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('motDePasseHash')->getData());
            $user->setMotDePasseHash($hashedPassword);
            // Set additional user data
            $user->setEstactif(true);
            $user->setDateInscription(new \DateTime());
            // Save the user to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // Redirect to a success page or do something else
            return $this->redirectToRoute('registration_success');
        }
    
        return $this->render('log.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }



    #[Route('/register/success', name: 'registration_success')]
    public function registrationSuccess(): Response
    {
        return $this->render('registration/success.html.twig');
    }

    #[Route('/dashboard/profile/{pseudo}', name: 'app_profile')]
    public function profile($pseudo): Response
    {
        return $this->render('/AdminDash/UserManagement/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/dashboard/users', name: 'app_users')]
    public function view(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(Utilisateurs::class)->findAll();
        return $this->render('/AdminDash/UserManagement/users.html.twig', [
            'users' => $users,
        ]);
    }
}
