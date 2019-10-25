<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Interface UserLoaderInterface
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :data OR u.email = :data')
            ->setParameter('data', $username)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
        // /**
        //  * @return Utilisateur[] Returns an array of Utilisateur objects
        //  */
        /*
        public function findByExampleField($value)
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.exampleField = :val')
                ->setParameter('val', $value)
                ->orderBy('p.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }
        */

        /*
        public function findOneBySomeField($value): ?Utilisateur
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
        */
}
