<?php

namespace App\Repository;

use App\Entity\ContactPartenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContactPartenaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactPartenaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactPartenaire[]    findAll()
 * @method ContactPartenaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactPartenaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactPartenaire::class);
    }

//    /**
//     * @return ContactPartenaire[] Returns an array of ContactPartenaire objects
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
    public function findOneBySomeField($value): ?ContactPartenaire
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
