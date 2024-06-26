<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('public/index.html.twig');
    }

    #[Route('/books', name: 'catalog')]
    public function catalog(): Response
    {
        return $this->render('public/catalog.html.twig', [
            'books' => $this->em->getRepository(Book::class)->findAll()
        ]);
    }

    #[Route('/books/{slug}', name: 'details')]
    public function details(string $slug): Response
    {
        $book = $this->em->getRepository(Book::class)->findOneBy(['slug' => $slug]);
        if (!$book) return $this->redirectToRoute('catalog');

        return $this->render('public/details.html.twig', [
            'book' => $book
        ]);
    }
}
