<?php

namespace App\Repository;

use App\Entity\Diplome;
use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * @return Collection|Formation[]
     */
    public function findResult($search)  {
        if ($search["departement"] != "all" && count($search["departement"]) == 0) {
            $search["departement"] = ["75", "77", "78", "91", "92", "93", "94", "95"];
        }
        $tmp = $this->createQueryBuilder('o')
            ->join('o.diplome', 'diplome')
            ->join('diplome.domaine', 'domaine')
            ->andWhere('domaine.name = :val' )
            ->setParameter('val', $search["category"])
            ->andWhere('o.date >= :now')
            ->setParameter('now', new \DateTime('now'));
        if ($search["departement"] != "all"){
            $tmp->andWhere(':val2 LIKE CONCAT(:val3, SUBSTRING(o.postal_code, 1, 2), :val3)')
                ->setParameter('val2', implode(",", $search["departement"]))
                ->setParameter("val3", '%');
        }
        if ($search[str_replace(" ", "", strtolower($search["category"]))] != "Formation"){
            $tmp->andWhere('diplome.name = :val4' )
                ->setParameter('val4', $search[str_replace(" ", "", strtolower($search["category"]))]);
        }
        return $tmp->getQuery()->execute();
    }
    // /**
    //  * @return Formation[] Returns an array of Formation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
