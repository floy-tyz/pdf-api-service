<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use IteratorAggregate;

/**
 * @template T of object
 * @method EntityInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityInterface[]    findAll()
 * @method EntityInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends IteratorAggregate<array-key, T>
 */
interface RepositoryInterface extends ObjectRepository
{
    public function save(EntityInterface $entity, bool $flush = true): void;

    public function remove(EntityInterface $entity, bool $flush = true): void;

    public function getPagination(QueryBuilder $queryBuilder, int $page, int $itemsPerPage): array;
}
