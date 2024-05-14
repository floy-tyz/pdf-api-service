<?php

namespace App\Repository;

use App\Entity\Conversion;
use App\Service\Conversion\Interface\ConversionRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conversion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversion[]    findAll()
 * @method Conversion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversionRepository extends AbstractRepository implements ConversionRepositoryInterface
{
    protected const ENTITY_CLASS = Conversion::class;

    protected ?string $alias = 'conversion';

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, self::ENTITY_CLASS);
    }
}
