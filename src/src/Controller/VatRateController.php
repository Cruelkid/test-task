<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use App\Services\VatRateService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VatRateController
 * @package App\Controller
 * @Route ("/api", name="vat_rate_api", condition="request.headers.get('locale') != null")
 */
class VatRateController extends AbstractController
{
    private VatRateService $rateService;
    private const ERROR_MESSAGE = [
        'status' => 500,
        'message' => 'Something went wrong'
    ];

    public function __construct(VatRateService $rateService)
    {
        $this->rateService = $rateService;
    }

    /**
     * @param int $rateId
     * @return JsonResponse
     * @Route ("/vatRates/{rateId}", name="_getVatRate", methods={"GET"})
     */
    public function getVatRate(int $rateId): JsonResponse
    {
        $data = $this->rateService->getVatRate($rateId);
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route ("/vatRates", name="_get", methods={"GET"})
     */
    public function getVatRates(): JsonResponse
    {
        $data = $this->rateService->getAllVatRates();
        return $this->json($data);
    }

    /**
     * @param Request $request
     * @param CountryRepository $countryRepository
     * @return JsonResponse
     * @Route ("/vatRates", name="_create", methods={"POST"})
     */
    public function addVatRate(Request $request, CountryRepository $countryRepository): JsonResponse
    {
        try {
            $result = $this->rateService->addVatRate($request, $countryRepository);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        } catch (\Exception $e) {
            $result = ['status' => 500, 'message' => $e->getMessage()];
        }

        return $this->json($result['message'], $result['status']);
    }

    /**
     * @param int $rateId
     * @param Request $request
     * @param CountryRepository $countryRepository
     * @return JsonResponse
     * @Route ("/vatRates/{rateId}", name="_update", methods={"PUT"})
     */
    public function updateVatRate(int $rateId, Request $request, CountryRepository $countryRepository): JsonResponse
    {
        try {
            $result = $this->rateService->updateVatRate($rateId, $request, $countryRepository);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        } catch (\Exception $e) {
            $result = ['status' => 500, 'message' => $e->getMessage()];
        }

        return $this->json($result['message'], $result['status']);
    }

    /**
     * @param int $rateId
     * @return JsonResponse
     * @Route ("/vatRates/{rateId}", name="_delete", methods={"DELETE"})
     */
    public function deleteVatRate(int $rateId): JsonResponse
    {
        try {
            $result = $this->rateService->deleteVatRate($rateId);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        }

        return $this->json($result['message'], $result['status']);
    }
}
