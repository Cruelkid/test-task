<?php

namespace App\Services;

use App\Entity\VatRate;
use App\Repository\CountryRepository;
use App\Repository\VatRateRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;

class VatRateService extends Service
{
    private VatRateRepository $rateRepository;

    public function __construct(VatRateRepository $rateRepository)
    {
        $this->rateRepository = $rateRepository;
    }

    public function getVatRate(int $rateId): ?array
    {
        return $this->rateRepository->createQueryBuilder('q')
            ->select('q', 'c')
            ->leftJoin('q.country', 'c')
            ->where('q.id = :rateId')
            ->setParameter('rateId', $rateId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getAllVatRates(): ?array
    {
        return $this->rateRepository->createQueryBuilder('q')
            ->select('q', 'c')
            ->leftJoin('q.country', 'c')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function addVatRate(Request $request, CountryRepository $countryRepository): array
    {
        $vatRate = new VatRate();

        $this->saveVatRate($request, $countryRepository, $vatRate);

        return [
            'status' => 200,
            'message' => 'VAT rate added successfully'
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function updateVatRate(int $rateId, Request $request, CountryRepository $countryRepository): array
    {
        $vatRate = $this->rateRepository->find($rateId);

        if (!$vatRate) {
            return [
                'status' => 404,
                'message' => 'VAT rate not found'
            ];
        }

        $this->saveVatRate($request, $countryRepository, $vatRate);

        return [
            'status' => 200,
            'message' => 'VAT rate updated successfully'
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function deleteVatRate(int $rateId): array
    {
        $vatRate = $this->rateRepository->find($rateId);

        if (!$vatRate) {
            return [
                'status' => 404,
                'message' => 'VAT rate not found'
            ];
        }

        $this->rateRepository->delete($vatRate);

        return [
            'status' => 200,
            'message' => 'VAT rate deleted successfully'
        ];
    }

    /**
     * @param Request $request
     * @param CountryRepository $countryRepository
     * @param VatRate $vatRate
     * @return VatRate|null
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    protected function saveVatRate(Request $request, CountryRepository $countryRepository, VatRate $vatRate): ?VatRate
    {
        $requestData = $this->parseJsonBody($request);
        $rate = $requestData->get('rate');
        $countryName = $requestData->get('country');
        $country = $countryRepository->findOneBy(['name' => $countryName]);

        $vatRate->setRate($rate);

        if ($country) {
            $vatRate->addCountry($country);
        }

        $this->rateRepository->save($vatRate);

        return $vatRate;
    }
}