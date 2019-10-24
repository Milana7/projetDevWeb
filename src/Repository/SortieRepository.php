<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Affiche toutes les sorties
     * @return Sortie[]
     */
    public function listSortiesAll()
    {
        return $this
            ->createQueryBuilder('sortie')
            ->innerJoin(Utilisateur::class, 'u')
            ->getQuery()
            ->getResult();
    }

    /**
     * Affiche les sorties par l'id d'organisateur de la sortie
     * @param $idOrg : identifiant de l'organisateur
     * @return Sortie[]
     */
    public function listByOrganiser($idOrg)
    {
        return $this
            ->createQueryBuilder('sortie')
            ->innerJoin( Utilisateur::class,'u')
            ->where('u.id = :id') //ou juste id
            ->setParameter('id', $idOrg)
            ->getQuery()
            ->getResult();


    }
}
