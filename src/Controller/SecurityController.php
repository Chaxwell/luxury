<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/auth")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="auth_login", methods={"GET","POST"})
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
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
     * @Route("/profile", name="auth_profile", methods={"GET"}))
     */
    public function profile(Request $request): Response
    {
        return $this->render('security/profile.html.twig', []);
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
