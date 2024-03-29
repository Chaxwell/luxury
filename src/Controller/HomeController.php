<?php

namespace App\Controller;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\UserRepository;
use App\Repository\JobOfferRepository;
use App\Repository\CandidatureRepository;
use App\Entity\JobOffer;
use App\Entity\Candidature;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(JobOfferRepository $jobOfferRepository)
    {
        $jobOffers = $jobOfferRepository->findBy([], null, 6);

        return $this->render('home/index.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }

    /**
     * @Route("/company", name="home_company")
     */
    public function company()
    {
        return $this->render('home/company.html.twig', []);
    }

    /**
     * @Route("/contact", name="home_contact")
     */
    public function contact()
    {
        return $this->render('home/contact.html.twig', []);
    }

    /**
     * @Route("/jobs", name="jobs_index")
     */
    public function jobsIndex(JobOfferRepository $jobOfferRepository)
    {
        $jobOffers = $jobOfferRepository->findBy(['active' => true]);

        return $this->render('jobs/index.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }

    /**
     * @Route("/jobs/{id}/show", name="jobs_show", methods={"GET"})
     */
    public function jobsShow(JobOffer $jobOffer, UserInterface $candidate, CandidatureRepository $candidatureRepository)
    {
        $candidature = $candidatureRepository->findOneBy(
            [
                'user' => $candidate->getId(),
                'jobOffer' => $jobOffer->getId()
            ]
        );

        return $this->render('jobs/show.html.twig', [
            'jobOffer' => $jobOffer,
            'candidature' => $candidature,
        ]);
    }

    /**
     * @Route("/candidature/{id}/new", name="new_candidature", methods={"GET"})
     */
    public function newCandidature(JobOffer $jobOffer, ObjectManager $objectManager, UserInterface $candidate, CandidatureRepository $candidatureRepository, UserRepository $userRepository)
    {
        $candidature = $candidatureRepository->findOneBy(
            [
                'user' => $candidate->getId(),
                'jobOffer' => $jobOffer->getId()
            ]
        );

        // If the candidate already applied to a job we redirect him with a warning message.
        if ($candidature) {
            return $this->redirectToRoute('jobs_show', [
                'id' => $jobOffer->getId(),
                'flashMessage' => $this->addFlash('warning', 'You already applied to this job.'),
            ]);
        }

        if (!$userRepository->isProfileComplete($candidate)) {
            return $this->redirectToRoute('jobs_show', [
                'id' => $jobOffer->getId(),
                'flashMessage' => $this->addFlash('warning', 'You can\'t apply to a job until your profile is complete.')
            ]);
        }

        $candidature = new Candidature();
        $candidature
            ->setUser($candidate)
            ->setJobOffer($jobOffer)
            ->setCreatedAt()
            ->setUpdatedAt();

        $objectManager->persist($candidature);
        $objectManager->flush();

        return $this->redirectToRoute('jobs_show', [
            'id' => $jobOffer->getId(),
            'flashMessage' => $this->addFlash('success', "You've applied to this job!"),
        ]);
    }
}
