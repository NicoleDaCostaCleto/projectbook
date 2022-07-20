<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/show_book/{id}", name="show_book")
     */
    public function showBook(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        return $this->render("show_book.html.twig", [

            'book' => $book
        ]);

    }

    /**
     * @Route("list_books", name="list_books")
     */
    public function listBooks(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render("list_books.html.twig", [
            'books' => $books
        ]);
    }


    /**
     * @Route("authors", name="list_authors")
     */
    public function listAuthors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();

        return $this->render('list_authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @Route("authors/{id}", name="show_author")
     */
    public function showAuthor($id, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find($id);

        return $this->render('show_author.html.twig', [
            'author' => $author
        ]);
    }
}
