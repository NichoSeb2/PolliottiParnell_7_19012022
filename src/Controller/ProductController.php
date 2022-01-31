<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController {
    private const PRODUCT_PER_PAGE = 5;

    #[Route('/api/products', name: 'product', methods: ['GET'], format: 'json')]
    public function products(Request $request, ProductRepository $productRepository): Response {
        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        return $this->json([
            'code' => 200, 
            'data' => $productRepository->search('asc', $page, self::PRODUCT_PER_PAGE)
        ], 200);
    }
}