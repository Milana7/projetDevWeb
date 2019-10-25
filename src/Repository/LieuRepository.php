<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Lieu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lieu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lieu[]    findAll()
 * @method Lieu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    /**
     * Retourne la liste des lieux appartenant à la ville dont l'id est passé en paramètre
     */
    public function findByIdVille($idVille)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.idVille = :val')
            ->setParameter('val', $idVille)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    /**
     * Retourne le lieu (+ sa ville) dont l'id est passé en paramètre
     */
    public function findById($idLieu){
        return $this->createQueryBuilder('l')
            ->select(array('l', 'v'))
            ->andWhere('l.id = :val')
            ->setParameter('val', $idLieu)
            ->join('l.idVille', 'v')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    // /**
    //  * @return Lieu[] Returns an array of Lieu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lieu
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
