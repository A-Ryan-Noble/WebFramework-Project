<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{id}/question", name="book_question", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function question(Request $request, Book $book): Response
    {
        // Gets the question value
        $question = $request->get('question');

        // valid if no value is empty
        $isValid = !empty($question);

        // Check if form is submitted as a POST method
        $isSubmitted = $request->isMethod('POST');

        if ($isValid && $isSubmitted) {
            // gets the username of the user logged in then adds it to the question
            $loggedIn = $this->getUser();
            $question = $question . " - Asked by " . $loggedIn;

            $book->setQuestions([$question]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            // return back to the given book's view
            $args = [
                'id' => $book->getId()
            ];
            return $this->redirectToRoute('book_show', $args);
        }

        return $this->render('user/question.html.twig', ['book' => $book]);
    }

    /**
     * @Route("/{id}/answer", name="book_answer", methods={"GET","POST"})
     */
    public function answer(Request $request, Book $book): Response
    {
        $question = $request->get(('questionId'));

        /*
         * Returns an array of the message that the user entered at 1 index.
         * With " - by (username)" in another
         */
        $q =explode( ' - ', $question);

        // Returns the user that entered said array. ie. returns what is after who it was asked by

        $q2 = explode('Asked by',$q[1]);

        $questionPart = $q[0];
        $userPart = $q2[1];

        // Gets the reply value
        $reply = $request->get('reply');

        // valid if no value is empty
        $isValid = !empty($reply);

        // Check if form is submitted as a POST method
        $isSubmitted = $request->isMethod('POST');

        if ($isValid && $isSubmitted) {
            // gets the username of the user logged in then adds it to the question
            $loggedIn = $this->getUser();

            $result = $userPart." asked: ''".$questionPart."''. ".$loggedIn." response".": ''".$reply."''";

            $book->setReplies([$result]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $args = [
                'id' => $book->getId(),
                'replies' => $result,
            ];
            return $this->redirectToRoute('book_show', $args);
        }
        return $this->render('user/questionAnswer.html.twig', [
            'book' => $book,
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/bid", name="book_bid", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function bid(Request $request, Book $book): Response
    {
        $template = 'user/bidding.html.twig';

        $args = [
            'book' => $book
        ];

        // Gets the Logged in user's bid
        $bidByUser = $request->get('bidAmount');

        // valid if no value is empty
        $isValid = !empty($bidByUser);

        // Check if form is submitted as a POST method
        $isSubmitted = $request->isMethod('POST');

        if ($isValid && $isSubmitted) {
            $currentBid = $book->getBid();

            if ($bidByUser <= $currentBid) {
                $this->addFlash(
                    'error',
                    'Your bid of € ' . $bidByUser . ' was too low, for it to be considered.'
                );
                return $this->render($template, $args);
            }

            $book->setBid($bidByUser);
            $book->addBidder($this->getUser());
            $book->setBidOnBy($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }
        return $this->render($template, $args);
    }
    /**
     * @Route("/bidding/book/{id}/accept", name="book_bidAccept", methods={"GET","POST"})
     */
    public function bidAcceptance(Request $request, Book $book): Response
    {
        if ($book->getBid() > 0)
        {
            $book->setBidAccepted(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        $args = [
            'userBid' => $book->getBidOnBy(),
            'book' => $book,
            'id' => $book->getId()
        ];

        return $this->redirectToRoute('book_show',$args);
    }

    /**
     * @Route("/{id}/ownedBooks", name="user_ownedBooks", methods={"GET"})
     */
    public function ownedBooks(User $user, BookRepository $bookRepository): Response
    {
        //Calls countBooksOfUser method in UserRepository passing user's Id from the form
        $userGotBooks = $bookRepository->countBooksOfUser($user->getId());

        $ownsAnBook = false; // Unless the user has at least a book this will be false

        if ($userGotBooks >0) {
            $ownsAnBook = true;
        }

        $template = 'book/booksOwned.html.twig';

        $args = [
            'user' => $user,
            'books'=>$bookRepository->findAll(),
            'userhasBook' => $ownsAnBook,
        ];

        return $this->render($template, $args);
    }
}