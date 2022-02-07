<?php

namespace App\Controller;

use App\Entity\Product;
use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Schema;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes\Response as APIResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes\Parameter as APIParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Tag(name: "Products")]
#[Security(name: "Bearer")]
class ProductController extends AbstractController {
    private const PRODUCT_PER_PAGE = 5;

    #[Route('/api/products', name: 'app_products_get', methods: ['GET'], format: 'json')]
    #[APIParameter(
        name: "page", 
        description: "The current page", 
        in: "query", 
        required: false, 
        schema: new Schema(null, null, null, null, null, null, "integer")
    )]
    #[APIResponse(
        response: 200, 
        description: "Returns a paginated list of products", 
    )]
    #[APIResponse(
        response: 401, 
        description: "JWT Token not found", 
    )]
    public function readAllProduct(Request $request, ProductRepository $productRepository): Response {
        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        return $this->json([
            'code' => 200, 
            'data' => $productRepository->search("asc", $page, self::PRODUCT_PER_PAGE)
        ], 200, [], ['groups' => "product"]);
    }

    #[Route('/api/products/{id}', name: 'app_product_get', methods: ['GET'], format: 'json')]
    #[ParamConverter("product", converter: "EntityParamConverter")]
    #[APIParameter(
        name: "id", 
        description: "The product id", 
        in: "path", 
        required: true, 
        schema: new Schema(null, null, null, null, null, null, "integer")
    )]
    #[APIResponse(
        response: 200, 
        description: "Returns the details of a product", 
    )]
    #[APIResponse(
        response: 401, 
        description: "JWT Token not found", 
    )]
    #[APIResponse(
        response: 404, 
        description: "No product found for the provided id", 
    )]
    public function readProduct(Product $product): Response {
        return $this->json([
            'code' => 200, 
            'data' => $product
        ], 200, [], ['groups' => "product"]);
    }
}
