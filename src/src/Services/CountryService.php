<?php

namespace App\Services;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Repository\LocaleRepository;
use App\Repository\VatRateRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CountryService
 * @package App\Services
 */
final class CountryService extends Service
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function getCountry(int $countryId): ?array
    {
        return $this->countryRepository->createQueryBuilder('q')
            ->select('q', 'v', 'l')
            ->leftJoin('q.vatRates', 'v')
            ->leftJoin('q.locale', 'l')
            ->where('q.id = :countryId')
            ->setParameter('countryId', $countryId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getAllCountries(): ?array
    {
        return $this->countryRepository->createQueryBuilder('q')
            ->select('q', 'v', 'l')
            ->leftJoin('q.vatRates', 'v')
            ->leftJoin('q.locale', 'l')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function addCountry(
        Request $request,
        LocaleRepository $localeRepository,
        VatRateRepository $rateRepository
    ): array {
        $country = new Country();

        $this->saveCountry($request, $localeRepository, $rateRepository, $country);

        return [
            'status' => 200,
            'message' => 'Country added successfully'
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function updateCountry(
        int $countryId,
        Request $request,
        LocaleRepository $localeRepository,
        VatRateRepository $rateRepository
    ): array {
        $country = $this->countryRepository->find($countryId);

        if (!$country) {
            return [
                'status' => 404,
                'message' => 'Country not found'
            ];
        }

        $this->saveCountry($request, $localeRepository, $rateRepository, $country);

        return [
            'status' => 200,
            'message' => 'Country updated successfully'
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function deleteCountry(int $countryId): array
    {
        $country = $this->countryRepository->find($countryId);

        if (!$country) {
            return [
                'status' => 404,
                'message' => 'Country not found'
            ];
        }

        $this->countryRepository->delete($country);

        return [
            'status' => 200,
            'message' => 'Country deleted successfully'
        ];
    }

    /**
     * @param Request $request
     * @param LocaleRepository $localeRepository
     * @param VatRateRepository $rateRepository
     * @param Country $country
     * @return Country|null
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function saveCountry(
        Request $request,
        LocaleRepository $localeRepository,
        VatRateRepository $rateRepository,
        Country $country
    ): ?Country {
        $requestData = $this->parseJsonBody($request);
        $name = $requestData->get('name');
        $localeCode = $requestData->get('locale');
        $locale = $localeRepository->findOneBy(['isoCode' => $localeCode]);
        $vatRateValues = $requestData->get('vatRates');
        $vatRates = $rateRepository->findBy(['rate' => $vatRateValues]);

        $country->setName($name);
        $country->setLocale($locale);

        if ($vatRates) {
            foreach ($vatRates as $vatRate) {
                $country->addVatRate($vatRate);
            }
        }

        $this->countryRepository->save($country);

        return $country;
    }
}