<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceAdminController extends AbstractController
{
    #[Route('/annonce/admin', name: 'app_annonce_admin')]
    public function index(): Response
    {
        return $this->render('annonce_admin/index.html.twig', [
            'controller_name' => 'AnnonceAdminController',
        ]);
    }
}
