<?php

namespace App\Repository;

use App\Entity\ContactPublic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContactPublic|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactPublic|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactPublic[]    findAll()
 * @method ContactPublic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactPublicRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactPublic::class);
    }

    public function findAllR()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return ContactPublic[] Returns an array of ContactPublic objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactPublic
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
