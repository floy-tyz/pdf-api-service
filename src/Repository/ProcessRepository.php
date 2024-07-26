<?php

namespace App\Repository;

use App\Entity\Process;
use App\Entity\User;
use App\Service\Process\Interface\ProcessRepositoryInterface;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Process|null find($id, $lockMode = null, $lockVersion = null)
 * @method Process|null findOneBy(array $criteria, array $orderBy = null)
 * @method Process[]    findAll()
 * @method Process[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcessRepository extends AbstractRepository implements ProcessRepositoryInterface
{
    protected const string ENTITY_CLASS = Process::class;

    protected ?string $alias = 'process';

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, self::ENTITY_CLASS);
    }

    private function getSizeSumOfProcessedFilesQuery(DateInterval $dateInterval): QueryBuilder
    {
        $qb = $this->createQueryBuilder($this->alias);

        $qb->select('CAST(SUM(CAST(files.size AS integer)) AS string)');

        $qb->leftJoin('process.files', 'files');

        $qb->andWhere('files.isUsed = true');
        $qb->andWhere('process.dateProcessed > :timeLimit');

        $qb->setParameter('timeLimit', (new DateTime())->sub($dateInterval));

        return $qb;
    }

    public function getSizeSumOfProcessedFilesByUser(UserInterface|User $user): string
    {
        // TODO: User dateInterval logic
        $dateInterval = new DateInterval('PT3M');

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return 0;
        }

        $qb = $this->getSizeSumOfProcessedFilesQuery($dateInterval);

        $qb->andWhere('process.owner = :owner');

        $qb->setParameter('owner', $user->getId());

        return $qb->getQuery()->getSingleScalarResult() ?? "0";
    }

    public function getSizeSumOfProcessedFilesByAnonymous(string $clientIp, DateInterval $dateInterval): string
    {
        $qb = $this->getSizeSumOfProcessedFilesQuery($dateInterval);

        $qb->andWhere('process.clientIp = :client_ip');

        $qb->setParameter('client_ip', $clientIp);

        return $qb->getQuery()->getSingleScalarResult() ?? "0";
    }
}
