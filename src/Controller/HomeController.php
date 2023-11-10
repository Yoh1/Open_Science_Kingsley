<?php

namespace App\Controller;

use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
       $publications = $entityManager->getRepository(Publication::class)->findAll();

        foreach ($publications as $publication){
            if(count($publication->getLikes()) >= 3) {

                return $this->render('home/index.html.twig', [
                    'publications' => $publications

                ]);
            }
        }

        return $this->render('home/AucunArticle.html.twig', [
            'publications' => $publications
        ]);


    }
}
