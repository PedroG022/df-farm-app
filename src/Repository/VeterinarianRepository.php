<?php

namespace App\Repository;

use App\Entity\Veterinarian;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Veterinarian>
 *
 * @method Veterinarian|null find($id, $lockMode = null, $lockVersion = null)
 * @method Veterinarian|null findOneBy(array $criteria, array $orderBy = null)
 * @method Veterinarian[]    findAll()
 * @method Veterinarian[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VeterinarianRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Veterinarian::class);
        $this->paginator = $paginator;
    }

    public function findAllPaginated(int $page, int $limit): PaginationInterface
    {
        $query = $this->createQueryBuilder('v')
            ->select('v')
            ->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            $limit
        );
    }

    public function findOneByCrmv(string $crmv): ?Veterinarian
    {
        return $this->createQueryBuilder('v')
            ->addSelect('v')
            ->andWhere('v.crmv = :crmv')
            ->setParameter('crmv', $crmv)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneById(Uuid $id): ?Veterinarian
    {
        return $this->createQueryBuilder('v')
            ->addSelect('v')
            ->andWhere('v.id = :id')
            ->setParameter('id', $id->toBinary())
            ->getQuery()
            ->getOneOrNullResult();
    }
}