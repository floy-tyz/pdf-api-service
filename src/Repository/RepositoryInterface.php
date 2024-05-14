<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use IteratorAggregate;

/**
 * @template T of object
 * @extends IteratorAggregate<array-key, T>
 */
interface RepositoryInterface extends ObjectRepository
{
    public function save(EntityInterface $entity, bool $flush = true): void;

    public function remove(EntityInterface $entity, bool $flush = true): void;

    public function getPagination(QueryBuilder $queryBuilder, int $page, int $itemsPerPage): array;
}
