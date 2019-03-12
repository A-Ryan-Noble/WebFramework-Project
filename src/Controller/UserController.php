<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        $template = 'user/view.html.twig';

        $args=[
        ];

        return $this->render($template,$args);
    }
}

