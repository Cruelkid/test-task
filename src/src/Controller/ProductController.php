<?php

namespace App\Controller;

use App\Repository\VatRateRepository;
use App\Services\ProductService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 * @Route ("/api", name="product_api", condition="request.headers.get('locale') != null")
 */
class ProductController extends AbstractController
{
    private ProductService $productService;
    private const ERROR_MESSAGE = [
        'status' => 500,
        'message' => 'Something went wrong'
    ];

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @param int $productId
     * @return JsonResponse
     * @Route ("/products/{productId}", name="_getProduct", methods={"GET"})
     */
    public function getProduct(int $productId): JsonResponse
    {
        $data = $this->productService->getProduct($productId);
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route ("/products", name="_get", methods={"GET"})
     */
    public function getProducts(): JsonResponse
    {
        $data = $this->productService->getAllProducts();
        return $this->json($data);
    }

    /**
     * @param Request $request
     * @param VatRateRepository $rateRepository
     * @return JsonResponse
     * @Route ("/products", name="_create", methods={"POST"})
     */
    public function addProduct(Request $request, VatRateRepository $rateRepository): JsonResponse
    {
        try {
            $result = $this->productService->addProduct($request, $rateRepository);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        } catch (\Exception $e) {
            $result = ['status' => 500, 'message' => $e->getMessage()];
        }

        return $this->json($result['message'], $result['status']);
    }

    /**
     * @param int $productId
     * @param Request $request
     * @param VatRateRepository $rateRepository
     * @return JsonResponse
     * @Route ("/products/{productId}", name="_update", methods={"PUT"})
     */
    public function updateProduct(int $productId, Request $request, VatRateRepository $rateRepository): JsonResponse
    {
        try {
            $result = $this->productService->updateProduct($productId, $request, $rateRepository);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        } catch (\Exception $e) {
            $result = ['status' => 500, 'message' => $e->getMessage()];
        }

        return $this->json($result['message'], $result['status']);
    }

    /**
     * @param int $productId
     * @return JsonResponse
     * @Route ("/products/{productId}", name="_delete", methods={"DELETE"})
     */
    public function deleteProduct(int $productId): JsonResponse
    {
        try {
            $result = $this->productService->deleteProduct($productId);
        } catch (OptimisticLockException|ORMException $e) {
            $result = [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
        }

        return $this->json($result['message'], $result['status']);
    }
}
