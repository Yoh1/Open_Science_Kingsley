<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Publication;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class ProofreadingController extends AbstractController
{
    #[Route('/relecture', name: 'relecture')]
    #[IsGranted('ROLE_USER')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $publications = $entityManager->getRepository(Publication::class)->findAll();
        dump($publications);

        return $this->render('proofreading/index.html.twig', [
            'publications' => $publications
        ]);
    }

    #[Route('/relecture/{id}', name: 'relecture_show')]
    #[IsGranted('ROLE_USER')]
    public function show(EntityManagerInterface $entityManager,int $id): Response
    {


      $form =  $this->createForm(CommentType::class);
      $comment = new Comment();
        if($form->isSubmitted() && $form->isValid()){
            $commentCurrent = $form->get('comment')->getData();
            $comment->setPublication();

        }

        $publication = $entityManager->getRepository(Publication::class)->find($id);
        return $this->render('proofreading/publicationShow.html.twig', [
            'publication' => $publication
        ]);
    }
}
