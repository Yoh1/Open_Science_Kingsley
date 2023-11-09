<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser()->getId();
        $publications = $entityManager->getRepository(Publication::class)->findBy(['user' => $currentUser]);
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'publications' => $publications
        ]);
    }

    #[Route('/dashboard/create', name: 'publication')]
    public function CreatePublication(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdf = $form->get('upload')->getData();
            if (!$pdf) {
                throw  new \Exception('error');
            } else {
                $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdf->guessExtension();

                try {
                    $pdf->move(
                        $this->getParameter('pdf_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $publication->setUser($currentUser);
                $publication->setTitle($form->get('title')->getData());
                $publication->setUpload($newFilename);
            }
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }
        return $this->render('dashboard/createPublication.html.twig', [
            'form' => $form
        ]);
    }


}
