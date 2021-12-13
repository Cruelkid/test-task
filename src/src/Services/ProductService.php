<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\VatRateRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;

class ProductService extends Service
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProduct(int $productId): ?array
    {
        return $this->productRepository->createQueryBuilder('q')
            ->select('q', 'v')
            ->leftJoin('q.vatRate', 'v')
            ->where('q.id = :productId')
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getAllProducts(): ?array
    {
        return $this->productRepository->createQueryBuilder('q')
            ->select('q', 'v')
            ->leftJoin('q.vatRate', 'v')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function addProduct(Request $request, VatRateRepository $rateRepository): array
    {
        $product = new Product();

        $this->saveProduct($request, $rateRepository, $product);

        return [
            'status' => 200,
            'message' => 'Product added successfully'
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function updateProduct(int $productId, Request $request, VatRateRepository $rateRepository): array
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            return [
                'status' => 404,
                'message' => 'Product not found'
            ];
        }

        $this->saveProduct($request, $rateRepository, $product);

        return [
            'status' => 200,
            'message' => 'Product updated successfully'
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function deleteProduct(int $productId): array
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            return [
                'status' => 404,
                'message' => 'Product not found'
            ];
        }

        $this->productRepository->delete($product);

        return [
            'status' => 200,
            'message' => 'Product deleted successfully'
        ];
    }

    /**
     * @param Request $request
     * @param VatRateRepository $rateRepository
     * @param Product $product
     * @return Product|null
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function saveProduct(Request $request, VatRateRepository $rateRepository, Product $product): ?Product
    {
        $requestData = $this->parseJsonBody($request);
        $name = $requestData->get('name');
        $description = $requestData->get('description');
        $price = $requestData->get('price');
        $vatRateValue = $requestData->get('vatRate');
        $vatRate = $rateRepository->findOneBy(['rate' => $vatRateValue]);

        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);

        if ($vatRate) {
            $product->setVatRate($vatRate);
        }

        $this->productRepository->save($product);

        return $product;
    }
}