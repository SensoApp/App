<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    /**
     * @Route("/frontend/{reactRouting}", name="frontend", defaults={"reactRouting": null})
     */
    public function index()
    {
        return $this->render('frontend/index.html.twig');
    }
}
