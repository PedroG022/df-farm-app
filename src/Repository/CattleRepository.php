<?php

namespace App\Repository;

use App\Entity\Cattle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cattle::class);
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

//    /**
//     * @return Cattle[] Returns an array of Cattle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cattle
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
