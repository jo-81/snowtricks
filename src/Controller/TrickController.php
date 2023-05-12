<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    #[Route('/tricks', name: 'trick.list')]
    public function index(): Response
    {
        return $this->render('trick/index.html.twig');
    }
}
