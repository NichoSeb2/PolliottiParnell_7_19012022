<?php

namespace App\Controller;

use App\Entity\Product;
use OpenApi\Attributes\Tag;
use OpenApi\Annotations as OA;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Tag(name: "Products")]
#[Security(name: "Bearer")]
class ProductController extends AbstractController {
    private const PRODUCT_PER_PAGE = 5;

    #[Route('/api/products', name: 'app_products_get', methods: ['GET'], format: 'json')]
    /**
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The current page",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns a paginated list of products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"product"}))
     *     )
     * )
     */
    public function productsGet(Request $request, ProductRepository $productRepository): Response {
        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        return $this->json([
            'code' => 200, 
            'data' => $productRepository->search("asc", $page, self::PRODUCT_PER_PAGE)
        ], 200);
    }

    #[Route('/api/products/{id}', name: 'app_product_get', methods: ['GET'], format: 'json')]
    /**
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The product id",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of a product",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"product"}))
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="No product found for the provided id.",
     * )
     */
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
