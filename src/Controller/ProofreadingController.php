<?php

namespace App\Controller;

use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProofreadingController extends AbstractController
{
    #[Route('/relecture', name: 'relecture')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $publications = $entityManager->getRepository(Publication::class)->findAll();

        return $this->render('proofreading/index.html.twig', [
            'publications' => $publications
        ]);
    }

    #[Route('/relecture/{id}', name: 'relecture_show')]
    public function show(EntityManagerInterface $entityManager,int $id): Response
    {
        $publication = $entityManager->getRepository(Publication::class)->find($id);

        return $this->render('proofreading/publicationShow.html.twig', [
            'publication' => $publication
        ]);
    }
}
