<?php

namespace App\Controller;

use App\Entity\EpreuveCompetition;
use App\Form\EpreuveCompetitionType;
use App\Repository\EpreuveCompetitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/epreuve/competition')]
final class EpreuveCompetitionController extends AbstractController
{
    #[Route(name: 'app_epreuve_competition_index', methods: ['GET'])]
    public function index(EpreuveCompetitionRepository $epreuveCompetitionRepository): Response
    {
        return $this->render('epreuve_competition/index.html.twig', [
            'epreuve_competitions' => $epreuveCompetitionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_epreuve_competition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $epreuveCompetition = new EpreuveCompetition();
        $form = $this->createForm(EpreuveCompetitionType::class, $epreuveCompetition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($epreuveCompetition);
            $entityManager->flush();

            return $this->redirectToRoute('app_epreuve_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('epreuve_competition/new.html.twig', [
            'epreuve_competition' => $epreuveCompetition,
            'form' => $form,
        ]);
    }

    #[Route('/{competition}', name: 'app_epreuve_competition_show', methods: ['GET'])]
    public function show(EpreuveCompetition $epreuveCompetition): Response
    {
        return $this->render('epreuve_competition/show.html.twig', [
            'epreuve_competition' => $epreuveCompetition,
        ]);
    }

    #[Route('/{competition}/edit', name: 'app_epreuve_competition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EpreuveCompetition $epreuveCompetition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EpreuveCompetitionType::class, $epreuveCompetition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_epreuve_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('epreuve_competition/edit.html.twig', [
            'epreuve_competition' => $epreuveCompetition,
            'form' => $form,
        ]);
    }

    #[Route('/{competition}', name: 'app_epreuve_competition_delete', methods: ['POST'])]
    public function delete(Request $request, EpreuveCompetition $epreuveCompetition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$epreuveCompetition->getCompetition(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($epreuveCompetition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_epreuve_competition_index', [], Response::HTTP_SEE_OTHER);
    }
}
