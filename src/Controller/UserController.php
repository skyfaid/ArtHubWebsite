<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Utilisateurs;
use App\Form\EditUserType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\UserRegistrationFormType;

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
    /*#[Route('/profile/{pseudo}', name: 'app_profileuser')]
    public function editUser(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser(); // Assuming this retrieves the logged-in user
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Save user info logic
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('app_profileuser',['pseudo' => $user->getUserIdentifier()]);
        }
    
        return $this->render('/ClientHome/UserManagement/profile.html.twig', [
            'editUserForm' => $form->createView(),
        ]);
    }*/

    /*#[Route('/profile/{pseudo}', name: 'app_profileuser')]
    public function editUser(string $pseudo, Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $entityManager = $doctrine->getManager();
        $userRepository = $entityManager->getRepository(Utilisateurs::class);
        $user = $userRepository->findOneBy(['pseudo' => $pseudo]); // Retrieve user by pseudo
    
        if (!$user) {
            throw $this->createNotFoundException('No user found for pseudo ' . $pseudo);
        }
    
        // Extract country code and phone number if phone number exists
        $countryCode = '';
$phoneNumber = '';
if ($user->getPhoneNumber()) {
    $fullPhoneNumber = $user->getPhoneNumber();
    $pattern = '/^\+(\d{1,4})(\d+)$/'; // Regex to split into country code and phone number
    if (preg_match($pattern, $fullPhoneNumber, $matches)) {
        $countryCode = $matches[1] ?? ''; // Country code
        $phoneNumber = $matches[2] ?? ''; // Local phone number
    }
}

$form = $this->createForm(EditUserType::class, $user, [
    'country_code' => $countryCode,
    'phone_number' => $phoneNumber,
]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Combine country code and phone number before saving
            $user->setPhoneNumber('+' . $form->get('countryCode')->getData() . $form->get('phoneNumber')->getData());
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('app_profileuser', ['pseudo' => $user->getPseudo()]);
        }
    
        return $this->render('/ClientHome/UserManagement/profile.html.twig', [
            'editUserForm' => $form->createView(),
        ]);
    }*/
    
    #[Route('/profile/{pseudo}', name: 'app_profileuser')]
public function editUser(string $pseudo, Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
{
    $entityManager = $doctrine->getManager();
    $userRepository = $entityManager->getRepository(Utilisateurs::class);
    $user = $userRepository->findOneBy(['pseudo' => $pseudo]); // Retrieve user by pseudo

    if (!$user) {
        throw $this->createNotFoundException('No user found for pseudo ' . $pseudo);
    }

    // Extract country code and phone number if phone number exists
    $countryCode = '';
    $phoneNumber = '';
    if ($user->getPhoneNumber()) {
        $fullPhoneNumber = $user->getPhoneNumber();
        $pattern = '/^\+(\d{1,4})(\d+)$/'; // Regex to split into country code and phone number
        if (preg_match($pattern, $fullPhoneNumber, $matches)) {
            $countryCode = $matches[1] ?? ''; // Country code
            $phoneNumber = $matches[2] ?? ''; // Local phone number
        }
    }

    $form = $this->createForm(EditUserType::class, $user, [
        'country_code' => $countryCode,
        'phone_number' => $phoneNumber,
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Check if a new password is being typed
        $newPassword = $form->get('newPassword')->getData();
        if (!empty($newPassword)) {
            // If a new password is provided, ensure the current password is also provided
            $currentPassword = $form->get('currentPassword')->getData();
            if (empty($currentPassword) || !$passwordHasher->isPasswordValid($user, $currentPassword)) {
                // Add error to the current password field if it's invalid
                $form->get('currentPassword')->addError(new FormError('Invalid current password.'));
                // Render the form again with the error message
                return $this->render('/ClientHome/UserManagement/profile.html.twig', [
                    'editUserForm' => $form->createView(),
                ]);
            }
        
            // Hash the new password and update it in the user entity
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setMotDePasseHash($hashedPassword);
        }
        

        // Handle image upload
        $profileImageFile = $form->get('urlImageProfil')->getData();
        if ($profileImageFile ) {
            $originalFilename = pathinfo($profileImageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // Use Symfony's Slugger to generate a unique filename
            
            $newFilename = $originalFilename.'.'.$profileImageFile->guessExtension();

            // Move the file to the desired location
            try {
                $profileImageFile->move(
                    $this->getParameter('profile_images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle file upload error
                // Display some error message to the user
            }

            // Store the filename in the database
            $user->setUrlImageProfil('/images/'.$newFilename);
        }

        // Combine country code and phone number before saving
        $user->setPhoneNumber('+' . $form->get('countryCode')->getData() . $form->get('phoneNumber')->getData());

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Profile updated successfully!');
        return $this->redirectToRoute('app_profileuser', ['pseudo' => $user->getPseudo()]);
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
