<?php

namespace App\Repository;

use App\Entity\VccVendor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VccVendor|null find($id, $lockMode = null, $lockVersion = null)
 * @method VccVendor|null findOneBy(array $criteria, array $orderBy = null)
 * @method VccVendor[]    findAll()
 * @method VccVendor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VccVendorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VccVendor::class);
    }

    // /**
    //  * @return VccVendor[] Returns an array of VccVendor objects
    //  */
    public function findByFilter($filters)
    {
        $builder = $this->createQueryBuilder('vcc_vendor');
        foreach ($filters as $filterKey => $filter){
            $builder = $builder->andWhere('vcc_vendor.' . $filterKey . ' = :' . $filterKey)
                ->setParameter($filterKey, $filter);
        }
        return $builder
            ->orderBy('vcc_vendor.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?VccVendor
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
