<?php

namespace App\Controller;

use App\Entity\Epreuve;
use App\Form\EpreuveType;
use App\Repository\EpreuveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/epreuve')]
final class EpreuveController extends AbstractController
{
    #[Route(name: 'app_epreuve_index', methods: ['GET'])]
    public function index(EpreuveRepository $epreuveRepository): Response
    {
        return $this->render('epreuve/index.html.twig', [
            'epreuves' => $epreuveRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_epreuve_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $epreuve = new Epreuve();
        $form = $this->createForm(EpreuveType::class, $epreuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($epreuve);
            $entityManager->flush();

            return $this->redirectToRoute('app_epreuve_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('epreuve/new.html.twig', [
            'epreuve' => $epreuve,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_epreuve_show', methods: ['GET'])]
    public function show(Epreuve $epreuve): Response
    {
        return $this->render('epreuve/show.html.twig', [
            'epreuve' => $epreuve,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_epreuve_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Epreuve $epreuve, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EpreuveType::class, $epreuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_epreuve_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('epreuve/edit.html.twig', [
            'epreuve' => $epreuve,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_epreuve_delete', methods: ['POST'])]
    public function delete(Request $request, Epreuve $epreuve, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$epreuve->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($epreuve);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_epreuve_index', [], Response::HTTP_SEE_OTHER);
    }
}
