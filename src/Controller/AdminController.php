<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserRepository $userRepository): Response
    {
        // Gets all the values in the DIY form
        $userName = $request->get('userName');
        $password = $request->get('pass');
        $roleInput = $request->get('roleInput');

        // valid if no value is empty
        $isValid = !empty($userName) && !empty($password) && !empty($roleInput);

        // Check if form is submitted as a POST method
        $isSubmitted = $request->isMethod('POST');

        // if the name doesn't exist then this will be be null
        $usernameExists = $userRepository->searchForUsername($userName);

        // if SUBMITTED & VALID - go ahead and create new object
        if ($isSubmitted && $isValid)
        {
            /*
             * If username already exists in the DB then it returns it otherwise it is in DB this null.
             *  I.e. username entered must be new otherwise it results in error message
            */
            if(is_null($usernameExists) == false) {
                $this->addFlash(
                    'error',
                    'Username is already taken'
                );
                return $this->render('admin/new.html.twig');
            }

            $user = new User();

            $user->setUsername($userName);

            // password - and encoding
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);

            $user->setRoles([$roleInput]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }
        return $this->render('admin/new.html.twig');
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user ,BookRepository $bookRepository): Response
    {
        $template = 'admin/show.html.twig';

        // Gets the latest book's title and author of user
        $bookTitle = $bookRepository->usersLatestBookTitle($user->getId());
        $bookAuthor = $bookRepository->usersLatestBookAuthor($user->getId());

        $bookTotal = $bookRepository->countBooksOfUser($user->getId()); // Amount of the user's total book

        $args = [
            'user' => $user,
            'userId'=> $user->getId(),
            'bookAmount' => $bookTotal,
            'titleOfBooks'=> $bookTitle,
            'authorOfBooks'=> $bookAuthor,
        ];

        return $this->render($template,$args);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        // Gets all the values in the DIY form
        $username = $request->get('userName');
        $password = $request->get('pass');
        $roleInput = $request->get('roleInput');

        // valid if no value is empty
        $isValid = !empty($username) && !empty($password) && !empty($roleInput);

        // Check if form is submitted as a POST method
        $isSubmitted = $request->isMethod('POST');

        // if SUBMITTED & VALID - go ahead and create new object
        if ($isSubmitted && $isValid)
        {
            $user->setUsername($username);

            $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);

            $user->setRoles([$roleInput]);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        $template = 'admin/edit.html.twig';

        $args =[
            'user' => $user,
        ];

        return $this->render($template,$args);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user,BookRepository $bookRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $bookRepository->deleteUsersBooks($user->getId()); // removes the given user's books

            $entityManager->remove($user); // removes the user
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_index');
    }
}