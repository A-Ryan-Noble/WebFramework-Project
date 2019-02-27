<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        return $this->render('user/view.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
