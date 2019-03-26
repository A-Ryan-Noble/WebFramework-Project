<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;


// Custom create and edit type
use App\Form\BookType2;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/books", name="all_books", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showAll(BookRepository $bookRepository): Response
    {
        return $this->render('book/showAll.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"POST", "GET"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $book = new Book();

        $book->setBid(0); // For aesthetic in tables

        $book->setUser($this->getUser()); // Logged in user is default choice

        $book->setBidAccepted(false); // set false here to avoid choose un the form

        $form = $this->createForm(BookType2::class, $book);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('all_books');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('all_books');
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('all_books');
    }

    /**
     * @Route("/{id}/book_question", name="book_question", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function question(Request $request, Book $book): Response
    {
        // Gets the question value
        $question = $request->get('question');

        // valid if no value is empty
        $isValid = !empty($question);

        // was form submitted with POST method?
        $isSubmitted = $request->isMethod('POST');

        if ($isValid && $isSubmitted)
        {
            echo $question;

//            $comments = $book->getComments();
            // Set the comment of the book
            //$book->setComments([$comment]);

            $this->getDoctrine()->getManager()->flush();

            // return back to the given book's view
            return $this->redirect('book_show',$book);
//            return $this->render('book/show.html.twig',[
//                'book'=>$book,
//            ]);
        }

//        echo "test";
        /*$form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('all_books');
        }
*/
        return $this->render('book/questions.html.twig', [
            'book' => $book,
            //'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/book_bid", name="book_bid", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function bid(Request $request, Book $book): Response
    {
        // Gets the Logged in user's bid
        $bidByUser = $request->get('bidAmount');

        echo '€'.$bidByUser.' by '.$this->getUser();
        return $this->render('book/bidding.html.twig', [
            'book' => $book,
        ]);
    }
}