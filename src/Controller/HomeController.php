<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', []);
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
    public function jobsIndex()
    {
        return $this->render('jobs/index.html.twig', []);
    }

    /**
     * @Route("/jobs/show", name="jobs_show")
     */
    public function jobsShow()
    {
        return $this->render('jobs/show.html.twig', []);
    }
}
