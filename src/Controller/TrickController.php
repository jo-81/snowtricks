<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    #[Route('/tricks', name: 'trick.list', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('trick/index.html.twig');
    }

    #[Route('/tricks/{slug}', name: 'trick.show', methods: ['GET'])]
    public function show(Trick $trick, TrickRepository $trickRepository): Response
    {
        if (! $trick->isValided() || ! $trick->isPublished() ) {
            throw $this->createNotFoundException();
        }

        /* @phpstan-ignore-next-line */
        $tricksSameCategory = $trickRepository->findByCategory($trick->getCategory(), $trick->getId());

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'tricksSameCategory' => $tricksSameCategory,
        ]);
    }
}
