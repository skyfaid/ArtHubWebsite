<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\TwilioClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\FormError;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use App\Form\EditUserType;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\UserRegistrationFormType;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;


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
            $pattern = '/^\+(\d{1,3})(\d+)$/'; // Regex to split into country code and phone number
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
            if ($profileImageFile) {
                $originalFilename = pathinfo($profileImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Use Symfony's Slugger to generate a unique filename
                $newFilename = $originalFilename . '.' . $profileImageFile->guessExtension();
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
                $user->setUrlImageProfil('/images/' . $newFilename);
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

    #[Route('/forgotpassword', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Fetch Twilio credentials from environment variables
        $twilioSid = $_ENV['TWILIO_SID'];
        $twilioToken = $_ENV['TWILIO_TOKEN'];
        $twilioFrom = $_ENV['TWILIO_FROM'];
        // Create an instance of TwilioClient with the fetched credentials
        $twilioClient = new TwilioClient($twilioSid, $twilioToken, $twilioFrom);
        // Get the email from the request
        $email = $request->request->get('email');
        // Find the user by email
        $user = $entityManager->getRepository(Utilisateurs::class)->findOneByEmail($email);
        // If user exists, generate a reset code, update it in the database, and send it via SMS
        if ($user) {
            $resetCode = mt_rand(1000, 9999);
            $user->setResetCode((string)$resetCode);
            $expiryDate = new \DateTime();
            $expiryDate->modify('+3 minutes');
            $user->setResetCodeExpires($expiryDate);
            $entityManager->flush();
            // Send the reset code via SMS using the TwilioClient service
            $twilioClient->sendSms($user->getPhoneNumber(), "Your password reset code is: $resetCode");
            // Add flash message and redirect to reset password route with email as query parameter
            $this->addFlash('success', 'A reset code has been sent to your phone.');
            return $this->redirectToRoute('app_reset_password', ['email' => $email]);
        }
        // If user doesn't exist, render the forgot password form
        return $this->render('/ClientHome/UserManagement/forget_password.html.twig');
    }

    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Get the email and test code from the query parameters
        $email = $request->query->get('email');
        $testcode = $request->query->get('test');
        // Find the user by email
        $user = $entityManager->getRepository(Utilisateurs::class)->findOneByEmail($email);
        $testcode = $user->getResetCode();
        $expiryDate = $user->getResetCodeExpires();

        // Check if the request is POST
        if ($request->isMethod('POST')) {
            // Get the reset code and new password from the request
            $code = $request->request->get('code');
            $newPassword = $request->request->get('newPassword');

            // Validate reset code and user existence
            if (!$user || $testcode !== $code) {
                $this->addFlash('error', 'Invalid or expired reset code.');
                return $this->redirectToRoute('app_reset_password', ['email' => $email, 'test' => $testcode]);
            }

            $now = new \DateTime();
            if ($expiryDate < $now) {
                $this->addFlash('error', 'Reset code has expired. Please request a new one.');
                return $this->redirectToRoute('app_forgot_password');
            }

            // Update user's password and reset code in the database
            $user->setMotDePasseHash($passwordHasher->hashPassword($user, $newPassword));
            $user->setResetCode(null);

            $entityManager->flush();

            // Add success flash message and redirect to login page
            $this->addFlash('success', 'Your password has been updated.');
            return $this->redirectToRoute('app_login');
        }

        // Render the reset password form
        return $this->render('/ClientHome/UserManagement/reset_password.html.twig', ['email' => $email, 'test' => $testcode]);
    }
    #[Route('/resend-code', name: 'app_resend_code')]
    public function resendCode(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Fetch Twilio credentials from environment variables
        $twilioSid = $_ENV['TWILIO_SID'];
        $twilioToken = $_ENV['TWILIO_TOKEN'];
        $twilioFrom = $_ENV['TWILIO_FROM'];
        // Create an instance of TwilioClient with the fetched credentials
        $twilioClient = new TwilioClient($twilioSid, $twilioToken, $twilioFrom);
        // Get the email from the request
        $email = $request->query->get('email');
        // Find the user by email
        $user = $entityManager->getRepository(Utilisateurs::class)->findOneByEmail($email);

        // If user exists, generate a new reset code, update it in the database, and send it via SMS
        if ($user) {
            $resetCode = mt_rand(1000, 9999);
            $user->setResetCode((string)$resetCode);
            $expiryDate = new \DateTime();
            $expiryDate->modify('+3 minutes');
            $user->setResetCodeExpires($expiryDate);

            $entityManager->flush();
            $twilioClient->sendSms($user->getPhoneNumber(), "Your password reset code is: $resetCode");
            // Add flash message and redirect to reset password route with email as query parameter
            $this->addFlash('success', 'A new reset code has been sent to your phone.');
            return $this->redirectToRoute('app_reset_password', ['email' => $email]);
        }
        // If user doesn't exist, redirect to the forgot password route
        return $this->redirectToRoute('app_forgot_password');
    }


    #[Route('/ajax/users/list', name: 'ajax_users_list')]
    public function ajaxUsersList(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Get the parameters from DataTables request
        $start = $request->query->getInt('start', 0);
        $length = $request->query->getInt('length', 10);
        $search = $request->query->get('search', ['value' => ''])['value'];
        $order = $request->query->get('order', [['column' => '0', 'dir' => 'asc']]);

        // Determine which column to sort by (default is by 'joined' column)
        $sortColumns = ['u.nom', 'u.pseudo', 'u.role', 'u.dateInscription'];
        $orderColumn = $sortColumns[$order[0]['column']] ?? 'u.dateInscription';

        // Create a QueryBuilder instance for pagination
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from(Utilisateurs::class, 'u')
            ->setFirstResult($start)
            ->setMaxResults($length);

        // Add search functionality if there is a search value
        if (!empty($search)) {
            $qb->andWhere('u.nom LIKE :search OR u.pseudo LIKE :search OR u.email LIKE :search OR u.prenom LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // Add ordering
        $qb->orderBy($orderColumn, $order[0]['dir']);

        // Create Paginator instance
        $paginator = new DoctrinePaginator($qb, $fetchJoinCollection = false);

        // Format the data as needed by DataTables
        $formattedData = [];
        foreach ($paginator as $user) {
            $userHtml = '<div class="d-flex align-items-center">';
            $userHtml .= '<div class="me-3">';
            $userHtml .= '<img src="' . ($user->getUrlImageProfil() ? $user->getUrlImageProfil() : 'path/to/default/image.jpg') . '" class="avatar avatar-sm" alt="' . $user->getPrenom() . '">';
            $userHtml .= '</div>';
            $userHtml .= '<div>';
            $userHtml .= '<h6 class="mb-0">' . $user->getNom() . ' ' . $user->getPrenom() . '</h6>';
            $userHtml .= '<p class="text-secondary mb-0">' . $user->getEmail() . '</p>';
            $userHtml .= '</div></div>';
            
            $formattedData[] = [
                'user' => $userHtml,
                'pseudo_gender' => $user->getPseudo() . ' / ' . $user->getGender(),
                'role' => $user->getRole(),
                'joined' => $user->getDateInscription()->format('Y-m-d'),
                'action' => sprintf(
                    '<a href="javascript:;" class="text-secondary font-weight-bold text-xs ban-user" data-user-id="%d"><i class="%s"></i></a>',
                    $user->getUtilisateurId(),
                    $user->isEstactif() ? 'fas fa-check' : 'fas fa-ban'
                )
                

            ];
        }

        // Prepare the response for DataTables
        $response = [
            'draw' => intval($request->query->get('draw', 0)),
            'recordsTotal' => $em->getRepository(Utilisateurs::class)->count([]),
            'recordsFiltered' => count($paginator),
            'data' => $formattedData,
        ];

        return new JsonResponse($response);
    }

    #[Route('/ajax/toggle-active/{id}', name: 'ajax_toggle_active')]
public function toggleActive(int $id, EntityManagerInterface $em): JsonResponse
{
    $userRepository = $em->getRepository(Utilisateurs::class);
    $user = $userRepository->find($id);

    if (!$user) {
        return new JsonResponse(['success' => false, 'message' => 'User not found.']);
    }

    // Toggle the 'estActif' field
    $user->setEstactif(!$user->isEstactif());
    $em->flush();

    $status = $user->isEstactif() ? 'unbanned' : 'banned';
    return new JsonResponse(['success' => true, 'message' => "User has been {$status}.", 'newStatus' => $user->isEstactif()]);
}


}
