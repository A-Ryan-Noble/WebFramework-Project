<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
      // $template = 'default/homepage.html.twig';
        return $this->render('default/homepage.html.twig', [
            // no arguments
        ]);
    }
}