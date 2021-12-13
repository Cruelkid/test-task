<?php

namespace App\Controller;

use App\Repository\LocaleRepository;
use App\Repository\VatRateRepository;
use App\Services\CountryService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class CountryController
 * @package App\Controller
 * @Route ("/api", name="country_api", condition="request.headers.get('locale') != null")
 */
class CountryController extends AbstractController
{
    private CountryService $countryService;
    private const ERROR_MESSAGE = [
        'status' => 500,
        'message' => 'Something went wrong'
    ];

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * @param int $countryId
     * @return JsonResponse
     * @Route ("/countries/{countryId}", name="_getCountry", methods={"GET"})
     */
    public function getCountry(int $countryId): JsonResponse
    {
        $data = $this->countryService->getCountry($countryId);
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route ("/countries", name="_get", methods={"GET"})
     */
    public function getCountries(): JsonResponse
    {
        $data = $this->countryService->getAllCountries();
        return $this->json($data);
    }

    /**
     * @param Request $request
     * @param LocaleRepository $localeRepository
     * @param VatRateRepository $rateRepository
     * @return JsonResponse
     * @Route ("/countries", name="_create", methods={"POST"})
     */
    public function addCountry(
        Request $request,
        LocaleRepository $localeRepository,
        VatRateRepository $rateRepository
    ): JsonResponse {
        try {
            $result = $this->countryService->addCountry($request, $localeRepository, $rateRepository);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        } catch (\Exception $e) {
            $result = ['status' => 500, 'message' => $e->getMessage()];
        }

        return $this->json($result['message'], $result['status']);
    }

    /**
     * @param int $countryId
     * @param Request $request
     * @param LocaleRepository $localeRepository
     * @param VatRateRepository $rateRepository
     * @return JsonResponse
     * @Route ("/countries/{countryId}", name="_update", methods={"PUT"})
     */
    public function updateCountry(
        int $countryId,
        Request $request,
        LocaleRepository $localeRepository,
        VatRateRepository $rateRepository
    ): JsonResponse {
        try {
            $result = $this->countryService->updateCountry($countryId, $request, $localeRepository, $rateRepository);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        } catch (\Exception $e) {
            $result = ['status' => 500, 'message' => $e->getMessage()];
        }

        return $this->json($result['message'], $result['status']);
    }

    /**
     * @param int $countryId
     * @return JsonResponse
     * @Route ("/countries/{countryId}", name="_delete", methods={"DELETE"})
     */
    public function deleteCountry(int $countryId): JsonResponse
    {
        try {
            $result = $this->countryService->deleteCountry($countryId);
        } catch (OptimisticLockException|ORMException $e) {
            $result = [
                'status' => 500,
                'message' => 'Something went wrong'
            ];
        }

        return $this->json($result['message'], $result['status']);
    }
}
