<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PiController extends AbstractController
{
    #[Route('/pi', name: 'app_pi')]
    public function index(): Response
    {
        return $this->render('pi/index.html.twig', [
            'controller_name' => 'PiController',
        ]);
    }
}
