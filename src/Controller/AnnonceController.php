<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\Annonce1Type;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\ORM\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    private $sesssion = 1; 
    private $is_admin = 0  ;
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository, Request $request): Response
    {
    $username = $request->query->get('username'); // Get the username from the query parameter

    if ($username) {
        $annonces = $annonceRepository->findBy(['username' => $username]);
    } else {
        $annonces = $annonceRepository->findAll();
    }
    if  ($this->is_admin == 1) {
    return $this->render('annonce_admin/index.html.twig', [
        'annonces' => $annonces,
    ]);
    }
    else  {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
        }
}

    #[Route('/new', name: 'app_annonce_new', methods: ['GET'])]
    public function form(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/new.html.twig');
    }

    #[Route('/new/submit', name: 'app_annonce_submit', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {

        $em= $doctrine->getManager();

        $annonce = new Annonce();
        $amount = $request->request->get('montant');
        $Price = $request->request->get('prix');
        $name = $request->request->get('username'); 
        $paymentmethod = $request->request->get('paymentMethod');
        // $amount = $request->request->get('montant');

        $annonce->setMontant($amount);
        $annonce->setPrix($Price);
        $annonce->setUsername($name);
        $annonce->setStatus("En attente");
        $annonce->setDatetime(new \DateTime());
        $annonce->setPaymentMethod($paymentmethod);
        try {
            $em->persist($annonce);
            $em->flush();
    
            // Add success flash message
            $this->addFlash('success', 'Announcement added successfully!');
            return $this->redirectToRoute('app_annonce_index'); // Redirect to a route where you list announcements or show the added announcement
        } catch (\Exception $e) {
            // Add error flash message
            $this->addFlash('error', 'Failed to add announcement: ' . $e->getMessage());
        }
    
        
        return $this->redirectToRoute('app_annonce_new');
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

   #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Annonce $annonce, EntityManagerInterface $doctrine): Response
{
    if ($request->isMethod('POST')) {
        // Directly access POST data
        $annonce->setUsername($request->request->get('username'));
        $annonce->setMontant($request->request->get('montant'));
        $annonce->setPrix($request->request->get('prix'));
        $annonce->setStatus($request->request->get('status'));

        try {
            $doctrine->flush();
            return new JsonResponse(['success' => true, 'message' => 'Announcement updated successfully!']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Failed to update announcement: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    // If GET request, return data for filling in the form for initial data load via AJAX
    if ($request->isMethod('GET')) {
        return new JsonResponse([
            'id' => $annonce->getId(),
            'username' => $annonce->getUsername(),
            'montant' => $annonce->getMontant(),
            'prix' => $annonce->getPrix(),
            'status' => $annonce->getStatus(),
            'datetime' => $annonce->getDatetime()->format('Y-m-d H:i:s') // Assuming datetime is not null
        ]);
    }

    // Default return (could be removed, depends on your route design)
    return $this->redirectToRoute('app_annonce_index');
}

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
   
        public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
        {
            try {
                $entityManager->remove($annonce);
                $entityManager->flush();
                return new JsonResponse(['message' => 'Announcement deleted successfully'], Response::HTTP_OK);
            } catch (\Exception $e) {
                // Log the error here if you have a logger or just return an error response
                return new JsonResponse(['message' => 'Failed to delete announcement: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
           
            
         
        }
    
    // Remove the unmatched closing brace
    // }




// Remove the unmatched closing brace
// }
