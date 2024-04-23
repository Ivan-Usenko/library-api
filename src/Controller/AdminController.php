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
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    #[Route('/remove/book/{id}', name: 'remove_book')]
    public function removeBook(int $id): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);
        $this->em->remove($book);
        $this->em->flush();
        return $this->redirectToRoute('catalog');
    }

    #[Route('/export', name: 'export')]
    public function export(): Response
    {
        $books = $this->em->getRepository(Book::class)->findAll();
        
        $booksFormated = [];
        foreach ($books as $book) {
            $genres = '';
            for ($i = 0; $i < sizeof($book->getGenres()); $i++) {
                $genres .= $book->getGenres()[$i]->getName() . ( $i == sizeof($book->getGenres()) - 1 ? '' : ', ');
            }
            $authors = '';
            for ($i = 0; $i < sizeof($book->getAuthors()); $i++) {
                $authors .= $book->getAuthors()[$i]->getInitials() . ( $i == sizeof($book->getAuthors()) - 1 ? '' : ', ');
            }
            $bookArr = [
                'Назва' => $book->getTitle(),
                'Дата виходу' => $book->getReleaseDate()->format('d/m/Y'),
                'Жанри' => $genres,
                'Автори' => $authors,
            ];
            $booksFormated[] = $bookArr;
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $csv = $serializer->serialize($booksFormated, 'csv');

        $response = new Response($csv);
        $response->headers->set('Content-Encoding', 'UTF-8');
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=sample.csv');
        return $response;
    }
}
