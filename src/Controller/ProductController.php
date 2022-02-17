<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/api/products')]
class ProductController extends AbstractController {
    private const PRODUCT_PER_PAGE = 5;

    public function __construct(private SerializerInterface $serializer) {}

    #[Route('', name: 'app_products_get', methods: ['GET'], format: 'json')]
    public function readAllProduct(Request $request, ProductRepository $productRepository): Response {
        $page = (int) $request->query->get("page");
        $page = ($page > 0) ? $page : 1;

        $response = [
            'code' => 200,
            'data' => $productRepository->search("asc", $page, self::PRODUCT_PER_PAGE)
        ];

        return new Response($this->serializer->serialize($response, "json", SerializationContext::create()->setGroups(["product"])), 200);
    }

    #[Route('/{id}', name: 'app_product_get', methods: ['GET'], format: 'json')]
    #[ParamConverter("product", converter: "EntityParamConverter")]
    public function readProduct(Product $product): Response {
        $response = [
            'code' => 200,
            'data' => $product
        ];

        return new Response($this->serializer->serialize($response, "json", SerializationContext::create()->setGroups(["product"])), 200);
    }
}
