<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
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
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
        if ($isSubmitted && $isValid) {

            $user = new User();
            $user->setUsername($username);

            // password - and encoding
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);
            $user->setRoles([$roleInput]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }
// if(username taken)
        //{
        //  $this->addFlash(
//            'error', 'Username taken already'
//        );

        return $this->render('user/new.html.twig');
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
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
        if ($isSubmitted && $isValid) {

            $user->setUsername($username);
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);
            $user->setRoles([$roleInput]);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
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