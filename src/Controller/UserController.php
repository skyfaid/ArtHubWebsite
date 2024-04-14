<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateurs;

use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\UserRegistrationFormType;
use App\Form\EditUserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/register', name: 'app_register')]
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
            return $this->redirectToRoute('app_login');
        }

        return $this->render('ClientHome/UserManagement/signup.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }



    #[Route('/register/success', name: 'registration_success')]
    public function registrationSuccess(): Response
    {
        return $this->render('registration/success.html.twig');
    }

    #[Route('/profileadmin/{pseudo}', name: 'app_profile')]
    public function profilead($pseudo): Response
    {
        return $this->render('/AdminDash/UserManagement/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /*#[Route('/profile/{pseudo}', name: 'app_profileuser')]
    public function profile($pseudo): Response
    {
        return $this->render('/ClientHome/UserManagement/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }*/
    #[Route('/profile/{pseudo}', name: 'app_profileuser')]
    public function editUser(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser(); // Assuming this retrieves the logged-in user
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('saveinfo')->isClicked()) {
                // Save user info logic
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Profile updated successfully!');
                return $this->redirectToRoute('app_profileuser');
            } elseif ($form->get('save')->isClicked()) {
                // Change password logic
                $newPassword = $form->get('newPassword')->getData(); // Assuming you have a field named 'newPassword'
                if ($newPassword) {
                    $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                    $user->setMotDePasseHash($hashedPassword);
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'Password updated successfully!');
                }
                return $this->redirectToRoute('app_profileuser');
            }
        }

        return $this->render('/ClientHome/UserManagement/profile.html.twig', [
            'editUserForm' => $form->createView(),
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


    #[Route('/delete-account', name: 'app_delete_account')]
    public function deleteAccount(EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): RedirectResponse
    {
        $user = $this->getUser();
        if ($user) {
            // Remove the user from the database
            $entityManager->remove($user);
            $entityManager->flush();

            // Log out the user
            $this->get('security.token_storage')->setToken(null);
            $this->get('session')->invalidate();

            // Redirect to the home page
            return $this->redirectToRoute('app_client_home');
        }

        // Redirect to login page if no user found
        return $this->redirectToRoute('app_login');
    }
}
