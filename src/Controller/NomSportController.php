<?php

namespace App\Controller;

use App\Entity\NomSport;
use App\Form\NomSportType;
use App\Repository\NomSportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nom-sport')]
final class NomSportController extends AbstractController
{
    #[Route(name: 'app_nom_sport_index', methods: ['GET'])]
    public function index(NomSportRepository $nomSportRepository): Response
    {
        return $this->render('nom_sport/index.html.twig', [
            'nom_sports' => $nomSportRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nom_sport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nomSport = new NomSport();
        $form = $this->createForm(NomSportType::class, $nomSport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($nomSport);
            $entityManager->flush();

            return $this->redirectToRoute('app_nom_sport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nom_sport/new.html.twig', [
            'nom_sport' => $nomSport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nom_sport_show', methods: ['GET'])]
    public function show(NomSport $nomSport): Response
    {
        return $this->render('nom_sport/show.html.twig', [
            'nom_sport' => $nomSport,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nom_sport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NomSport $nomSport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NomSportType::class, $nomSport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nom_sport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nom_sport/edit.html.twig', [
            'nom_sport' => $nomSport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nom_sport_delete', methods: ['POST'])]
    public function delete(Request $request, NomSport $nomSport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomSport->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($nomSport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nom_sport_index', [], Response::HTTP_SEE_OTHER);
    }
}
