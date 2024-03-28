<?php

namespace App\Repository;

use App\Entity\Farm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Farm>
 *
 * @method Farm|null find($id, $lockMode = null, $lockVersion = null)
 * @method Farm|null findOneBy(array $criteria, array $orderBy = null)
 * @method Farm[]    findAll()
 * @method Farm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FarmRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Farm::class);
        $this->paginator = $paginator;
    }

    public function findAllPaginated(int $page, int $limit): PaginationInterface
    {
        $query = $this->createQueryBuilder('f')
            ->select('f')
            ->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            $limit
        );
    }

    public function findOneById(Uuid $id): ?Farm
    {
        return $this->createQueryBuilder('f')
            ->select('f')
            ->andWhere('f.id = :id')
            ->setParameter('id', $id->toBinary())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByName(string $name): ?Farm
    {
        return $this->createQueryBuilder('f')
            ->select('f')
            ->andWhere('f.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}