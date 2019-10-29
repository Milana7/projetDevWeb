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
     * @return Sortie[]
     */
    public function listSortiesAll(FiltreSortie $filtreSortie)
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
            ->join('o.site', 'si');

        if ($filtreSortie->getNomSite() != null) {
            $requete
                ->where('dateLimiteInscription < :date')
                ->setParameter('nomSite', '%' . $filtreSortie->getNomSite() . '%');
        }

        if ($filtreSortie->getNomSortie() != null) {
            $requete
                ->andwhere('s.nom LIKE :nomSortie')
                ->setParameter('nomSortie', '%' . $filtreSortie->getNomSortie() . '%');
        }

        if ($filtreSortie->getDateDebut() != null) {
            $requete
                ->andwhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $filtreSortie->getDateDebut());
        }

        if ($filtreSortie->getDatefin() != null) {
            $requete
                ->andwhere('s.dateLimiteInscription <= :dateFin')
                ->setParameter('dateFin', $filtreSortie->getDatefin());
        }

/*        if ($filtreSortie->getMesSortiesOrg() == true) {
            $requete
                ->andwhere('s.organisateur');
        }*/

        if ($filtreSortie->getSortiesExpirees() == true) {
            $date = new \DateTime();
            $requete
                ->andwhere('s.dateLimiteInscription < :date')
                ->setParameter('date', $date);
        }

        return $requete->getQuery()->getResult();
    }

    /**
     * Affiche les sorties par l'id d'organisateur de la sortie
     * @param $idOrg : identifiant de l'organisateur
     * @return Sortie[]
     */
    public function listByOrganiser($idOrg)
    {
        //TODO à modifier une fois que la connexion admin est créée
        return $this
            ->createQueryBuilder('s')
            ->innerJoin('s.organisateur', 'u')
            ->where('s.organisateur = :id') //ou juste id
            ->andWhere('u.id = :id')
            ->setParameter('id', $idOrg)
            ->getQuery()
            ->getResult();
    }

    /**
     * Affiche les sorties auxquelles l'utilisateur(connecté) est inscrit
     * @param $idUti : identifiant de l'utilisateur
     * @return Sortie[]
     */
    public function listSortiesByUser($idOrg)
    {
        //TODO à modifier une fois que la connexion admin est créée
        return $this
            ->createQueryBuilder('s')
            ->innerJoin(Utilisateur::class, 'u')
            ->where('s.organisateur = :id') //ou juste id
            ->andWhere('u.id = :id')
            ->setParameter('id', $idOrg)
            ->getQuery()
            ->getResult();
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
