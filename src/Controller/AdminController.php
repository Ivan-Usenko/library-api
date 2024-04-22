<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/add/book', name: 'add_book')]
    public function addBook(): Response
    {
        return $this->render('admin/add_book.html.twig');
    }
}
