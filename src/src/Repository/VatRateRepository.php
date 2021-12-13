<?php

namespace App\Repository;

use App\Entity\VatRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method VatRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method VatRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method VatRate[]    findAll()
 * @method VatRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VatRateRepository extends ServiceEntityRepository
{
    private ValidatorInterface $validator;
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        parent::__construct($registry, VatRate::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws Exception
     */
    public function save(VatRate $vatRate): void
    {
        $errors = $this->validator->validate($vatRate);

        if (count($errors) > 0) {
            throw new Exception($errors);
        }

        $em = $this->getEntityManager();

        $em->persist($vatRate);
        $em->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(VatRate $vatRate): void
    {
        $em = $this->getEntityManager();

        $em->remove($vatRate);
        $em->flush();
    }
}
