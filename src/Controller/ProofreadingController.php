<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Publication;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


        return $this->render('proofreading/index.html.twig', [
            'publications' => $publications
        ]);
    }

    #[Route('/relecture/{id}', name: 'relecture_show')]
    public function show(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $comment = new Comment();
        $publication = $entityManager->getRepository(Publication::class)->find($id);

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCurrent = $this->getUser();
            if($userCurrent !== $publication.$this->getUser()){
                $commentCurrent = $form->get('comment')->getData();

                $comment->setComment($commentCurrent);
                $comment->setPublication($publication);
                $comment->setUser($userCurrent);

                $entityManager->persist($comment);
                $entityManager->flush();

                return  $this->redirectToRoute('home');
            } else {
                throw new \Exception('Un auteur ne peut ce commenter lui même');
            }

        }

        return $this->render('proofreading/publicationShow.html.twig', [
            'publication' => $publication,
            'form' => $form
        ]);
    }

    #[Route('/relecture/like/{id}', name: 'like')]
    #[IsGranted('ROLE_USER')]
    public function submitLike(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $publicationCurrent = $entityManager->getRepository(Publication::class)->findOneById($id);

        $userCurrent = $this->getUser();
        if($userCurrent !== $publicationCurrent->getUser()){
            $publicationCurrent->addLike($userCurrent);
            $entityManager->flush();
        } else {
            throw new Exception('Un user ne peut commenter sont propre article');
        }
        return  $this->redirectToRoute('relecture');
    }
}
