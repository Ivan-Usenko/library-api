<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/add/book', name: 'add_book')]
    public function addBook(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new AsciiSlugger();
            $book->setSlug($slugger->slug($book->getTitle())->lower());
            $this->em->persist($book);
            $this->em->flush();
            return $this->redirectToRoute('catalog');
        }

        return $this->render('admin/add_book.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/add/author', name: 'add_author')]
    public function addAuthor(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($author);
            $this->em->flush();
            return $this->redirectToRoute('catalog');
        }

        return $this->render('admin/add_author.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/add/genre', name: 'add_genre')]
    public function addGenre(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($genre);
            $this->em->flush();
            return $this->redirectToRoute('catalog');
        }

        return $this->render('admin/add_genre.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
