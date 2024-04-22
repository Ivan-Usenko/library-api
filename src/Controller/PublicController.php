<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('public/index.html.twig');
    }

    #[Route('/books', name: 'catalog')]
    public function catalog(): Response
    {
        return $this->render('public/catalog.html.twig');
    }

    #[Route('/books/{slug}', name: 'details')]
    public function details(string $slug): Response
    {
        return $this->render('public/details.html.twig');
    }
}
