<?php


namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookController extends AbstractController
{

    /**
     * @Route("/admin/book/{id}", name="admin_book")
     */
    public function showBook(BookRepository $bookRepository, $id)
    {
        // récupérer depuis la base de données un book en fonction d'un ID

        // la classe Repository me permet de faire des requête SELECT
        // dans la table associée avec son id
        $book = $bookRepository->find($id);


        return $this->render('admin/show_book.html.twig', [
            'book' => $book
        ]);

    }

    /**
     * @Route("/admin/books", name="admin_books")
     */
    public function listeBooks(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render('admin/list_books.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("/admin/insert-book", name="admin_insert_book")
     */
    public function insertBook(EntityManagerInterface $entityManager, Request $request)
    {
        $book = new Book();

        // j'ai utilisé en ligne de commandes "php bin/console make:form"
        // pour que Symfony me créé une classe qui contiendra "le costructeur", "le patron"
        // du formulaire pour créer un livre
        // $this->createForm pour créer un formulaire
        $form = $this->createForm(BookType::class,$book);

        //On donne à la variable qui contient le formulaire une instance
        // de la classe request pour récuperer l'input et faire
        // les setters automatiquement
        $form->handleRequest($request);

        // si l'input est valide on l'insère dans la bdd

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success','Livre enregistré');
        }
        // j'affiche mon twig, en lui passant une variable form
        return $this->render("admin/insert_book.html.twig", [
            'form'=> $form->createView()
        ]);

        }



    /**
     * @Route("/admin/books/delete/{id}", name="admin_delete_book")
     */
    public function deleteBook($id, BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        // je récupère le book en fonction de l'id dans l'url
        $book = $bookRepository->find($id);

        // je vérifie que la variable $book ne contient
        // pas null, donc que le book existe en bdd
        if (!is_null($book)) {
            // j'utilise l'entity manager pour supprimer le book
            $entityManager->remove($book);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien supprimé le livre !');
        } else {
            $this->addFlash('error', 'Livre introuvable ! ');

        }

        return $this->redirectToRoute('admin_books');
    }


    /**
     * @Route("/admin/books/update/{id}", name="admin_update_book")
     */
    public function updateBook($id, BookRepository $bookRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $book = new Book();
        $book->setPublishedAt(new \DateTime('NOW'));

        // j'ai utilisé en ligne de commandes "php bin/console make:form"

        $form = $this->createForm(BookType::class,$book);

        $form->handleRequest($request);

        // si le formulaire a été posté et que les données sont valide ont insere en bdd les inputs
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success','Livre enregistré');
        }



        // j'affiche mon twig, en lui passant une variable form
        return $this->render("admin/insert_book.html.twig", [
            'form'=> $form->createView()
        ]);

    }

    /**
     * @Route("/admin/books/search", name="admin_search_books")
     */
    public function searchBooks(Request $request, BookRepository $bookRepository)
    {
        // je récupère les valeurs du formulaire dans ma route
        $search = $request->query->get('search');

        // je vais créer une méthode dans BookRepository (searchByWord dans ce cas)
        // qui trouve un livre en fonction d'un mot dans son titre ou sa description
        $books = $bookRepository->searchByWord($search);

        // je renvoie un fichier twig en lui passant les livres trouvé
        // et je les affiche

        return $this->render('admin/search_books.html.twig', [
            'books' => $books
        ]);
    }

}