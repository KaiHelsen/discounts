<?php

namespace App\Controller;

use App\Model\Customer;
use App\Model\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscountCalculatorController extends AbstractController
{
    private const PRODUCTS = 'json/products.json';
    private const CUSTOMERS = 'json/customers.json';
    #[Route('/calculator', name: 'discount_calculator')]
    public function index(): Response
    {
        $customers = $this->fetchCustomers();
        $products = $this->fetchProducts();

        return $this->render('discount_calculator/index.html.twig', [
            'controller_name' => 'DiscountCalculatorController',
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    private function fetchCustomers(): array
    {
        $customerData = json_decode(file_get_contents(self::CUSTOMERS), true, 512, JSON_THROW_ON_ERROR);
        $customers = [];
        foreach ($customerData as $customer)
        {
            $customers[] = Customer::fromArray($customer);
        }
        return $customers;
    }

    private function fetchProducts(): array
    {
        $productData = json_decode(file_get_contents(self::PRODUCTS), true, 512, JSON_THROW_ON_ERROR);
        $products = [];
        foreach ($productData as $product)
        {
            $products[] = Product::fromArray($product);
        }
        return $products;
    }
}
