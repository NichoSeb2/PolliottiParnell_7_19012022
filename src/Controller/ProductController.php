<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/api/products')]
class ProductController extends AbstractController {
    private const PRODUCT_PER_PAGE = 5;

    #[Route('', name: 'app_products_get', methods: ['GET'], format: 'json')]
    public function readAllProduct(Request $request, ProductRepository $productRepository): Response {
        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        return $this->json([
            'code' => 200, 
            'data' => $productRepository->search("asc", $page, self::PRODUCT_PER_PAGE)
        ], 200, [], ['groups' => "product"]);
    }

    #[Route('/{id}', name: 'app_product_get', methods: ['GET'], format: 'json')]
    #[ParamConverter("product", converter: "EntityParamConverter")]
    public function readProduct(Product $product): Response {
        return $this->json([
            'code' => 200, 
            'data' => $product
        ], 200, [], ['groups' => "product"]);
    }
}
