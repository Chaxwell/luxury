<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_dashboard", methods={"GET"})
     */
    public function dashboard()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/candidates", name="admin_candidates", methods={"GET"})
     */
    public function candidates()
    {
        return $this->render('admin/candidates.html.twig');
    }
}
