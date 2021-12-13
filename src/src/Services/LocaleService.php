<?php

namespace App\Services;

use App\Entity\Locale;
use App\Repository\LocaleRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LocaleService extends Service
{
    private LocaleRepository $localeRepository;

    public function __construct(LocaleRepository $localeRepository)
    {
        $this->localeRepository = $localeRepository;
    }

    public function getLocale(int $localeId): ?array
    {
        return $this->localeRepository->createQueryBuilder('q')
            ->where('q.id = :localeId')
            ->setParameter('localeId', $localeId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getAllLocales(): ?array
    {
        return $this->localeRepository->createQueryBuilder('q')->getQuery()->getArrayResult();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function addLocale(Request $request): array
    {
        $locale = new Locale();

        $this->saveLocale($request, $locale);

        return [
            'status' => 200,
            'message' => 'Locale added successfully'
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function updateLocale(int $localeId, Request $request): array
    {
        $locale = $this->localeRepository->find($localeId);

        if (!$locale) {
            return [
                'status' => 404,
                'message' => 'Locale not found'
            ];
        }

        $this->saveLocale($request, $locale);

        return [
            'status' => 200,
            'message' => 'Locale updated successfully'
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[ArrayShape(['status' => "int", 'message' => "string"])] public function deleteLocale(int $localeId): array
    {
        $locale = $this->localeRepository->find($localeId);

        if (!$locale) {
            return [
                'status' => 404,
                'message' => 'Locale not found'
            ];
        }

        $this->localeRepository->delete($locale);

        return [
            'status' => 200,
            'message' => 'Locale deleted successfully'
        ];
    }

    /**
     * @param Request $request
     * @param Locale $locale
     * @return Locale|null
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function saveLocale(Request $request, Locale $locale): string|Locale
    {
        $requestData = $this->parseJsonBody($request);
        $name = $requestData->get('name');
        $isoCode = $requestData->get('isoCode');

        $locale->setName($name);
        $locale->setIsoCode($isoCode);

        $this->localeRepository->save($locale);

        return $locale;
    }
}