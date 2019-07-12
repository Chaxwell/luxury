<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\ClientType;
use App\Form\UserType;
use App\Repository\CandidatureRepository;
use App\Repository\ClientRepository;
use App\Repository\JobOfferRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Entity\Candidature;
use App\Form\CandidatureType;

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
            dd($form['resumeFile']->getData());
            $resumeFile = $form['resumeFile']->getData();

            if ($resumeFile) {
                $originalFilename = pathinfo($resumeFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$resumeFile->guessExtension();
            }

            try {
                $resumeFile->move(
                    $this->getParameter('profile_resume'),
                    $newFilename
                );
            } catch (FileException $e) {
                dd($e);
            }

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
     * @Route("/candidate/{id}/note", name="admin_note_candidate", methods={"POST"})
     */
    public function addNoteToCandidate(Request $request, User $candidate, ObjectManager $objectManager)
    {
        $candidate->setNote($request->request->get('note'));

        $objectManager->persist($candidate);
        $objectManager->flush();

        return $this->redirectToRoute('admin_candidates', [
            'flashMessage' => $this->addFlash('success', 'Note added.')
        ]);
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
            $resumeFile = $form['resumeFile']->getData();

            if ($resumeFile) {
                $originalFilename = pathinfo($resumeFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$resumeFile->guessExtension();
            }

            try {
                $resumeFile->move(
                    $this->getParameter('profile_resume'),
                    $newFilename
                );
            } catch (FileException $e) {
                dd($e);
            }

            $candidate
                ->setCreatedAt()
                ->setUpdatedAt()
                ->setPassword($hashedPassword)
                ->setResume($newFilename);

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
    public function deleteCandidate(User $candidate)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($candidate);
        $entityManager->flush();

        return $this->redirectToRoute('admin_candidates');
    }

    /**
     * @Route("/clients", name="admin_clients", methods={"GET"})
     */
    public function clients(ClientRepository $clientRepository)
    {
        $clients = $clientRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    /**
     * @Route("/client/new", name="admin_new_client", methods={"GET", "POST"})
     */
    public function newClient(Request $request, ObjectManager $objectManager)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client
                ->setCreatedAt()
                ->setUpdatedAt();

            $objectManager->persist($client);
            $objectManager->flush();

            return $this->redirectToRoute('admin_clients');
        }

        return $this->render('admin/client/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/client/{id}/note", name="admin_note_client", methods={"POST"})
     */
    public function addNoteToClient(Request $request, Client $client, ObjectManager $objectManager)
    {
        $client->setNote($request->request->get('note'));

        $objectManager->persist($client);
        $objectManager->flush();

        return $this->redirectToRoute('admin_clients', [
            'flashMessage' => $this->addFlash('success', 'Note added.')
        ]);
    }

    /**
     * @Route("/client/{id}/edit", name="admin_edit_client", methods={"GET", "POST"})
     */
    public function editClient(Request $request, Client $client, ObjectManager $objectManager)
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client
                ->setCreatedAt()
                ->setUpdatedAt();

            $objectManager->persist($client);
            $objectManager->flush();

            return $this->redirectToRoute('admin_clients');
        }

        return $this->render('admin/client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/client/{id}/delete", name="admin_delete_client", methods={"POST", "DELETE"})
     */
    public function deleteClient(Client $client)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($client);
        $entityManager->flush();

        return $this->redirectToRoute('admin_clients');
    }

    /**
     * @Route("/joboffers", name="admin_joboffers", methods={"GET"})
     */
    public function jobOffers(JobOfferRepository $jobOfferRepository)
    {
        $jobOffers = $jobOfferRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/joboffer/index.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }

    /**
     * @Route("/joboffer/new", name="admin_new_joboffer", methods={"GET", "POST"})
     */
    public function newjobOffer(Request $request, ObjectManager $objectManager)
    {
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer
                ->setCreatedAt()
                ->setUpdatedAt();

            $objectManager->persist($jobOffer);
            $objectManager->flush();

            return $this->redirectToRoute('admin_joboffers');
        }

        return $this->render('admin/joboffer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/joboffer/{id}/note", name="admin_note_joboffer", methods={"POST"})
     */
    public function addNoteTojobOffer(Request $request, JobOffer $jobOffer, ObjectManager $objectManager)
    {
        $jobOffer->setNote($request->request->get('note'));

        $objectManager->persist($jobOffer);
        $objectManager->flush();

        return $this->redirectToRoute('admin_joboffers', [
            'flashMessage' => $this->addFlash('success', 'Note added.')
        ]);
    }

    /**
     * @Route("/joboffer/{id}/edit", name="admin_edit_joboffer", methods={"GET", "POST"})
     */
    public function editjobOffer(Request $request, JobOffer $jobOffer, ObjectManager $objectManager)
    {
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer
                ->setCreatedAt()
                ->setUpdatedAt();

            $objectManager->persist($jobOffer);
            $objectManager->flush();

            return $this->redirectToRoute('admin_joboffers');
        }

        return $this->render('admin/joboffer/edit.html.twig', [
            'jobOffer' => $jobOffer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/joboffer/{id}/delete", name="admin_delete_joboffer", methods={"POST", "DELETE"})
     */
    public function deletejobOffer(JobOffer $jobOffer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($jobOffer);
        $entityManager->flush();

        return $this->redirectToRoute('admin_joboffers');
    }

    /**
     * @Route("/candidatures", name="admin_candidatures", methods={"GET"})
     */
    public function candidatures(CandidatureRepository $candidatureRepository)
    {
        $candidatures = $candidatureRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/candidature/index.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    /**
     * @Route("/candidature/new", name="admin_new_candidature", methods={"GET", "POST"})
     */
    public function newCandidature(Request $request, ObjectManager $objectManager)
    {
        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidature
                ->setCreatedAt()
                ->setUpdatedAt();

            $objectManager->persist($candidature);
            $objectManager->flush();

            return $this->redirectToRoute('admin_candidatures');
        }

        return $this->render('admin/candidature/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/candidature/{id}/note", name="admin_note_candidature", methods={"POST"})
     */
    public function addNoteToCandidature(Request $request, Candidature $candidature, ObjectManager $objectManager)
    {
        $candidature->setNote($request->request->get('note'));

        $objectManager->persist($candidature);
        $objectManager->flush();

        return $this->redirectToRoute('admin_joboffers', [
            'flashMessage' => $this->addFlash('success', 'Note added.')
        ]);
    }

    /**
     * @Route("/candidature{id}/edit", name="admin_edit_candidature", methods={"GET", "POST"})
     */
    public function editCandidature(Request $request, Candidature $candidature, ObjectManager $objectManager)
    {
        $form = $this->createForm(JobOfferType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidature
                ->setCreatedAt()
                ->setUpdatedAt();

            $objectManager->persist($candidature);
            $objectManager->flush();

            return $this->redirectToRoute('admin_candidatures');
        }

        return $this->render('admin/candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/candidature/{id}/delete", name="admin_delete_candidature", methods={"POST", "DELETE"})
     */
    public function deleteCandidature(Candidature $candidature)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($candidature);
        $entityManager->flush();

        return $this->redirectToRoute('admin_candidatures');
    }
}
