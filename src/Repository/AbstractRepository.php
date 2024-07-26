<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Webmozart\Assert\Assert;

/**
 * @template T<object>
 * @implements RepositoryInterface<T>
 */
abstract class AbstractRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public const int ITEMS_PER_PAGE = 25;

    protected ?string $alias = null;

    public function __construct(
        protected ManagerRegistry $managerRegistry,
        string $entityClass,
    ) {
        parent::__construct($managerRegistry, $entityClass);
    }

    public function save(EntityInterface $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EntityInterface $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function findAllWithPagination(int $page, int $itemsPerPage): array
    {
        if (!$this->alias) {
            throw new Exception('Не указан alias');
        }

        $qb = $this->createQueryBuilder($this->alias);

        return $this->getPagination($qb, $page, $itemsPerPage !== 0 ? $itemsPerPage : self::ITEMS_PER_PAGE);
    }

    public function getPagination(QueryBuilder $queryBuilder, int $page, int $itemsPerPage = self::ITEMS_PER_PAGE): array
    {
        Assert::positiveInteger($page);
        Assert::positiveInteger($itemsPerPage);

        $firstResult = ($page -1) * $itemsPerPage;

        $query = $queryBuilder->getQuery()
            ->setFirstResult($firstResult)
            ->setMaxResults($itemsPerPage);

        $paginator = new DoctrinePaginator($query);

        return [
            'success' => true,
            'entities' => $paginator->getQuery()->getResult(),
            'pagination' => [
                'current_page' => (int)$paginator->getCurrentPage(),
                'last_page' => (int)$paginator->getLastPage(),
                'total' => (int)$paginator->getTotalItems(),
            ]
        ];
    }
}
