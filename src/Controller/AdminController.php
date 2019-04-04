<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
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
    public function index(UserRepository $userRepository, BookRepository $bookRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        // Gets all the values in the DIY form
        $userName = $request->get('userName');
        $password = $request->get('pass');
        $roleInput = $request->get('roleInput');

        // valid if no value is empty
        $isValid = !empty($userName) && !empty($password) && !empty($roleInput);

        // was form submitted with POST method?
        $isSubmitted = $request->isMethod('POST');

        /*
         * Calls searchForUsername method in UserRepository passing userName from the form
         */
        $usernameTaken = $this->getDoctrine()->getRepository(User::class)->searchForUsername($userName);

        // if SUBMITTED & VALID - go ahead and create new object
        if ($isSubmitted && $isValid) {
            // If username isn't already in DB this null. I.e. username entered must be new otherwise re
            if ($usernameTaken != null) {
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

        $args = [
                'user' => $user,
                // These two arrays will be looped through on the page to be displayed as one list item
                'titleOfBooks'=>$bookRepository->searchForUserBookTitle($user->getId()),
                'authorOfBooks'=>$bookRepository->searchForUserBookAuthor($user->getId()),
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

        // was form submitted with POST method?
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

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}