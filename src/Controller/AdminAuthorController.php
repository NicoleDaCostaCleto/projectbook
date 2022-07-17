<?php


namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAuthorController extends AbstractController
{

    /**
     * @Route("admin/insert_author", name="admin_insert_author")
     */
    public function insertAuthor(EntityManagerInterface $entityManager, Request $request)
    {
        // je créé une instance de la classe d'entité Author
        $author = new Author();

        // j'ai utilisé en ligne de commandes "php bin/console make:form"
        // pour que Symfony me créé une classe qui contiendra "le costructeur", "le patron"
        // du formulaire pour créer l'author
        $form = $this->createForm(AuthorType::class,$author);

        // On donne à la variable qui contient le formulaire
        // une instance de la Classe request
        // pour que le formulaire puisse récupérer toutes les données
        // des inputs et faire les setters sur $article automatiquement
        $form->handleRequest($request);

        // si le formulaire a été posté et que les données sont valide ont insere en bdd les inputs
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success','Author enregistré');
        }


        // j'affiche mon twig, en lui passant une variable form
        return $this->render("admin/insert_author.html.twig", [
            'form'=> $form->createView()
        ]);

    }

    /**
     * @Route("admin/authors", name="admin_list_authors")
     */
    public function listAuthors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();

        return $this->render('admin/list_authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @Route("admin/authors/{id}", name="admin_show_author")
     */
    public function showAuthor($id, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find($id);

        return $this->render('admin/show_author.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/admin/authors/delete/{id}", name="admin_delete_author")
     */
    public function deleteAuthor($id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager)
    {
        $author = $authorRepository->find($id);

        $entityManager->remove($author);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez supprimé l\'author !');

        return $this->redirectToRoute('admin_list_authors');
    }

    /**
     * @Route("/admin/authors/update/{id}", name="admin_update_author")
     */
    public function updateAuthor($id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $author = $authorRepository->find($id);

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash("success", " Author modifié ! ");
        }

        //j'affiche le twig (créer au préalable en version insert) avec la variable form qui contient la vue du formulaire
        return $this->render("admin/update_author.html.twig", ["form" => $form->createView()]);


    }

    }