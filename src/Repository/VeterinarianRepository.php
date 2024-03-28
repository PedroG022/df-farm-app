<?php

namespace App\Repository;

use App\Entity\Veterinarian;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Veterinarian::class);
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

//    /**
//     * @return Veterinarian[] Returns an array of Veterinarian objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Veterinarian
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
