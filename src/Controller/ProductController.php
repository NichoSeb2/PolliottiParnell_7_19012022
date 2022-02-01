<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController {
    private const PRODUCT_PER_PAGE = 5;

    #[Route('/api/products', name: 'app_products_get', methods: ['GET'], format: 'json')]
    public function productsGet(Request $request, ProductRepository $productRepository): Response {
        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        return $this->json([
            'code' => 200, 
            'data' => $productRepository->search("asc", $page, self::PRODUCT_PER_PAGE)
        ], 200);
    }

    #[Route('/api/products/{id}', name: 'app_product_get', methods: ['GET'], format: 'json')]
    public function productGet(?Product $product): Response {
        if (is_null($product)) {
            throw new NotFoundHttpException("No product found for the provided id.");
        }

        return $this->json([
            'code' => 200, 
            'data' => $product
        ], 200);
    }
}
