<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // if user is logged in and they type the route of this page, their page will look like theit account page
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $template = 'security/successfulLogin.html.twig';

            $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());

            $amountOwned = $this->getDoctrine()->getRepository(Book::class)->countBooksOfUser($user);

            $args = [
                'userRoles' => $user->getRoles(),
                'bookAmount' => $amountOwned,
                'title' => 'Account'
            ];
            return $this->render($template, $args);
        }

        else {
            $template = 'default/homepage.html.twig';

            $args = [
                'title' => "Home"
            ];

            return $this->render($template, $args);
        }
    }
}