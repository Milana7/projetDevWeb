<?php

namespace App\Repository;

use App\Entity\FiltreSortie;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

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

    /**
     * Affiche toutes les sorties
     * @param int $userId
     * @return Sortie[]
     */
    public function listSortiesAll(FiltreSortie $filtreSortie, $userId)
    {
        /*        $requete = $this
                    ->createQueryBuilder('sortie')
                    ->innerJoin(Utilisateur::class, 'u')
                    ->where('sortie.nom = ?')
                    ->setParameter('nomSite', !assert($filtreSortie->getNomSite()))
                    ->getQuery()
                    ->getResult();*/

        $requete = $this
            ->createQueryBuilder('s')
            ->innerJoin('s.organisateur', 'o')
            ->leftJoin('s.utilisateurs', 'su')
            ->join('o.site', 'si');

        if ($filtreSortie->getNomSite() !== null) {
            $requete
                ->andwhere('dateLimiteInscription < :date')
                ->setParameter('nomSite', '%' . $filtreSortie->getNomSite() . '%');
        }

        if ($filtreSortie->getNomSortie() !== null) {
            $requete
                ->andwhere('s.nom LIKE :nomSortie')
                ->setParameter('nomSortie', '%' . $filtreSortie->getNomSortie() . '%');
        }

        if ($filtreSortie->getDateDebut() !== null) {
            $requete
                ->andwhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $filtreSortie->getDateDebut());
        }

        if ($filtreSortie->getDatefin() !== null) {
            $requete
                ->andwhere('s.dateLimiteInscription <= :dateFin')
                ->setParameter('dateFin', $filtreSortie->getDatefin());
        }

        if ($filtreSortie->getMesSortiesOrg() === true) {
            $requete
                ->andwhere('o.id = :id')
                ->setParameter('id', $userId);
        }

        if ($filtreSortie->getSortiesExpirees() === true) {
            $date = new \DateTime();
            $requete
                ->andwhere('s.dateLimiteInscription < :date')
                ->setParameter('date', $date);
        }

        if ($filtreSortie->getMesSortiesInscr() === true) {
            $requete
                ->andWhere('su.id = :id')
                ->setParameter('id', $userId);
        }

        if ($filtreSortie->getSortiesNonInscr() === true)
        {
            $requete
                ->andWhere('su.id != :id')
                ->setParameter('id', $userId);
        }
        //dump($requete->getQuery()->getSQL());


        return $requete->getQuery()->getResult();
    }



    /**
     * Affiche les sorties qui ont une date expirée
     * @param $idOrg : identifiant de l'organisateur
     * @return Sortie[]
     */
    public function listSortiesExpired()
    {
        $date = new \DateTime();
        return $this
            ->createQueryBuilder('sortie')
            ->where('sortie.dateLimiteInscription < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
}
