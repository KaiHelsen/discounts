<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscountCalculatorController extends AbstractController
{
    #[Route('/calculator', name: 'discount_calculator')]
    public function index(): Response
    {
        return $this->render('discount_calculator/index.html.twig', [
            'controller_name' => 'DiscountCalculatorController',
        ]);
    }
}
