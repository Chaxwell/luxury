<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CandidatureRepository;
use App\Repository\ClientRepository;
use App\Repository\JobOfferRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\JobCategory;

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
        $candidates = $userRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/candidate/index.html.twig', [
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

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordEncoder->encodePassword($candidate, $request->request->get('user')['password']);
            $candidate->setIsAdmin($request->request->get('user')['isAdmin']);
            $candidate->setAvailability($request->request->get('user')['availability']);

            $candidate
                ->setCreatedAt()
                ->setUpdatedAt()
                ->setPassword($hashedPassword);

            $objectManager->persist($candidate);
            $objectManager->flush();

            return $this->redirectToRoute('admin_candidates');
        }

        return $this->render('admin/candidate/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/candidate/{id}/note", name="admin_note_candidate", methods={"POST", "GET"})
     */
    public function addNote(Request $request, User $candidate, ObjectManager $objectManager)
    {
        $candidate->setNote($request->request->get('note'));

        $objectManager->persist($candidate);
        $objectManager->flush();

        return $this->redirectToRoute('admin_candidates');
    }

    /**
     * @Route("/candidate/{id}/edit", name="admin_edit_candidate", methods={"GET", "POST"})
     */
    public function editCandidate(Request $request, User $candidate, ObjectManager $objectManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordEncoder->encodePassword($candidate, $request->request->get('user')['password']);
            $candidate->setIsAdmin($request->request->get('user')['isAdmin']);
            $candidate->setAvailability($request->request->get('user')['availability']);

            $candidate
                ->setCreatedAt()
                ->setUpdatedAt()
                ->setPassword($hashedPassword);

            $objectManager->persist($candidate);
            $objectManager->flush();

            return $this->redirectToRoute('admin_candidates');
        }

        return $this->render('admin/candidate/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/candidate/{id}/delete", name="admin_delete_candidate", methods={"POST", "DELETE"})
     */
    public function deleteCandidate(Request $request, User $candidate)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($candidate);
        $entityManager->flush();

        return $this->redirectToRoute('admin_candidates');
    }
}
