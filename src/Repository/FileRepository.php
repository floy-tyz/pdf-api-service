<?php

namespace App\Repository;

use App\Entity\File;
use App\Service\File\Interface\FileRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method File|null find($id, $lockMode = null, $lockVersion = null)
 * @method File|null findOneBy(array $criteria, array $orderBy = null)
 * @method File[]    findAll()
 * @method File[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileRepository extends AbstractRepository implements FileRepositoryInterface
{
    protected const ENTITY_CLASS = File::class;

    protected ?string $alias = 'file';

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, self::ENTITY_CLASS);
    }

    /**
     * @return array|File[]
     */
    public function getUnusedFiles(): array
    {
        $qb = $this->createQueryBuilder($this->alias);

        $qb->andWhere('file.isUsed = 0');

        return $qb->getQuery()->getResult();
    }

    public function getConversionFiles(int $conversionId): array
    {
        $qb = $this->createQueryBuilder($this->alias);

        $qb->andWhere('file.isUsed = true');
        $qb->andWhere('file.conversion = :conversion_id');

        $qb->setParameter('conversion_id', $conversionId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getCombinedFile(int $conversionId): File
    {
        $qb = $this->createQueryBuilder($this->alias);

        $qb->andWhere('file.isUsed = true');
        $qb->andWhere('file.conversion = :conversion_id');

        $qb->setParameter('conversion_id', $conversionId);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
