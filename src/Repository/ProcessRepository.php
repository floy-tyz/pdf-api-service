<?php

namespace App\Repository;

use App\Entity\Process;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Process|null find($id, $lockMode = null, $lockVersion = null)
 * @method Process|null findOneBy(array $criteria, array $orderBy = null)
 * @method Process[]    findAll()
 * @method Process[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcessRepository extends AbstractRepository implements ProcessRepositoryInterface
{
    protected const ENTITY_CLASS = Process::class;

    protected ?string $alias = 'process';

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, self::ENTITY_CLASS);
    }
}
