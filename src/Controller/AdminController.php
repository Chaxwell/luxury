<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\ClientRepository;
use App\Repository\JobOfferRepository;
use App\Repository\CandidatureRepository;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_dashboard", methods={"GET"})
     */
    public function dashboard(UserRepository $userRepository, ClientRepository $clientRepository, JobOfferRepository $jobOfferRepository, CandidatureRepository $candidatureRepository)
    {
        $numberOfCandidates = $userRepository->countCandidates();
        $numberOfClients = $clientRepository->countClients();
        $numberOfJobOffers = $jobOfferRepository->countJobOffers();
        $numberOfCandidatures = $candidatureRepository->countCandidatures();

        return $this->render('admin/index.html.twig', [
            'numberOfCandidates' => $numberOfCandidates,
            'numberOfClients' => $numberOfClients,
            'numberOfJobOffers' => $numberOfJobOffers,
            'numberOfCandidatures' => $numberOfCandidatures,
        ]);
    }

    /**
     * @Route("/candidates", name="admin_candidates", methods={"GET"})
     */
    public function candidates(UserRepository $userRepository)
    {
        $candidates = $userRepository->findAll();

        return $this->render('admin/candidates.html.twig', [
            'candidates' => $candidates,
        ]);
    }

    /**
     * @Route("/candidate/new", name="admin_new_candidate", methods={"GET", "POST"})
     */
    public function newCandidate(Request $request, ObjectManager $objectManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $candidate = new User();
        $form = $this->createForm(UserType::class, $candidate);
        $form->handleRequest($request);

        // dd($candidate);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordEncoder->encodePassword($candidate, $request->request->get('user')['password']);

            $candidate
                ->setCreatedAt()
                ->setUpdatedAt()
                ->setPassword($hashedPassword);

            $objectManager->persist($candidate);
            $objectManager->flush();

            return $this->redirectToRoute('admin_candidates');
        }

        return $this->render('admin/new_candidate.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
