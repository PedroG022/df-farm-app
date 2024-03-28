<?php

namespace App\Repository;

use App\Entity\Cattle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Cattle>
 *
 * @method Cattle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cattle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cattle[]    findAll()
 * @method Cattle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CattleRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Cattle::class);
        $this->paginator = $paginator;
    }

    public function findAllPaginated(int $page, int $limit): PaginationInterface
    {
        $query = $this->createQueryBuilder('c')
            ->select('c')
            ->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            $limit
        );
    }

    public function findOneById(Uuid $id): ?Cattle
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id->toBinary())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByCode(string $code): ?Cattle
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->andWhere('c.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }
}