<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    #[Route('/user/selected', name: 'selected')]
    public function selected(): Response
    {
        return $this->render('user/selected.html.twig', [
            'books' => $this->security->getUser()->getSavedBooks()
        ]);
    }

    #[Route('/user/select/book/{id}', name: 'select_book')]
    public function select(int $id): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);
        $user = $this->security->getUser();
        $user->addSavedBook($book);
        $this->em->persist($user);
        $this->em->flush();
        return $this->redirectToRoute('details', [
            'slug' => $book->getSlug()
        ]);
    }

    #[Route('/user/remove/book/{id}', name: 'remove_selected')]
    public function remove(int $id): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);
        $user = $this->security->getUser();
        $user->removeSavedBook($book);
        $this->em->persist($user);
        $this->em->flush();
        return $this->redirectToRoute('selected');
    }
}
