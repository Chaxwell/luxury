<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\UserRepository;
use App\Form\RegistrationType;
use App\Form\ProfileType;
use App\Form\ChangePasswordType;
use App\Entity\User;

/**
 * @Route("/auth")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="auth_login", methods={"GET","POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/logyn.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="auth_logout", methods={"GET"})
     */
    public function logout(): void
    { }

    /**
     * @Route("/profile", name="auth_profile", methods={"GET", "POST"}))
     */
    public function profile(Request $request, UserInterface $candidate, ObjectManager $objectManager, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository): Response
    {
        $isProfileComplete = $userRepository->isProfileComplete($candidate);

        $form = $this->createForm(ProfileType::class, $candidate);
        $form->handleRequest($request);
        $formAccount = $this->createForm(ChangePasswordType::class, $candidate);
        $formAccount->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidate
                ->setUpdatedAt();

            $objectManager->persist($candidate);
            $objectManager->flush();
            return $this->redirectToRoute('auth_profile', [
                'flashMessage' => $this->addFlash('success', 'Profile edited with success.')
            ]);
        }

        if ($formAccount->isSubmitted() && $formAccount->isValid()) {
            $hashedPassword = $passwordEncoder->encodePassword($candidate, $request->request->get('change_password')['password']);
            $candidate
                ->setUpdatedAt()
                ->setPassword($hashedPassword);

            $objectManager->persist($candidate);
            $objectManager->flush();

            return $this->redirectToRoute('auth_profile', [
                'flashMessage' => $this->addFlash('success', 'Profile edited with success.')
            ]);
        }

        return $this->render('security/profile.html.twig', [
            'form' => $form->createView(),
            'formAccount' => $formAccount->createView(),
            'isProfileComplete' => $isProfileComplete,
        ]);
    }

    /**
     * @Route("/register", name="auth_register", methods={"GET", "POST"})
     */
    public function register(Request $request, ObjectManager $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordEncoder->encodePassword($user, $request->request->get('registration')['password']);
            $user
                ->setPassword($hashedPassword)
                ->setCreatedAt()
                ->setUpdatedAt();

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('auth_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
