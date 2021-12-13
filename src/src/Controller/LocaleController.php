<?php

namespace App\Controller;

use App\Services\LocaleService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LocaleController
 * @package App\Controller
 * @Route ("/api", name="locale_api", condition="request.headers.get('locale') != null")
 */
class LocaleController extends AbstractController
{
    private LocaleService $localeService;
    private const ERROR_MESSAGE = [
        'status' => 500,
        'message' => 'Something went wrong'
    ];

    public function __construct(LocaleService $localeService)
    {
        $this->localeService = $localeService;
    }

    /**
     * @param int $localeId
     * @return JsonResponse
     * @Route ("/locales/{localeId}", name="_getLocale", methods={"GET"})
     */
    public function getLocale(int $localeId): JsonResponse
    {
        $data = $this->localeService->getLocale($localeId);
        return $this->json($data);
    }

    /**
     * @return JsonResponse
     * @Route ("/locales", name="_get", methods={"GET"})
     */
    public function getLocales(): JsonResponse
    {
        $data = $this->localeService->getAllLocales();
        return $this->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route ("/locales", name="_create", methods={"POST"})
     */
    public function addLocale(Request $request): JsonResponse
    {
        try {
            $result = $this->localeService->addLocale($request);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        } catch (\Exception $e) {
            $result = ['status' => 500, 'message' => $e->getMessage()];
        }

        return $this->json($result['message'], $result['status']);
    }

    /**
     * @param int $localeId
     * @param Request $request
     * @return JsonResponse
     * @Route ("/locales/{localeId}", name="_update", methods={"PUT"})
     */
    public function updateLocale(int $localeId, Request $request): JsonResponse
    {
        try {
            $result = $this->localeService->updateLocale($localeId, $request);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        } catch (\Exception $e) {
            $result = ['status' => 500, 'message' => $e->getMessage()];
        }

        return $this->json($result['message'], $result['status']);
    }

    /**
     * @param int $localeId
     * @return JsonResponse
     * @Route ("/locales/{localeId}", name="_delete", methods={"DELETE"})
     */
    public function deleteLocale(int $localeId): JsonResponse
    {
        try {
            $result = $this->localeService->deleteLocale($localeId);
        } catch (OptimisticLockException|ORMException $e) {
            $result = self::ERROR_MESSAGE;
        }

        return $this->json($result['message'], $result['status']);
    }
}
