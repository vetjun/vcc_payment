<?php

namespace App\Repository;

use App\Entity\Bucket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Bucket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bucket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bucket[]    findAll()
 * @method Bucket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BucketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bucket::class);
    }

    // /**
    //  * @return Bucket[] Returns an Bucket object
    //  */

    public function findVendorByActivationDate($date): ? Bucket
    {
        $builder = $this->createQueryBuilder('bucket');
        $builder->select('bucket, MAX(bucket.limit_val) AS max_limit');
        $builder->where('bucket.start_date < :act_date')->setParameter('act_date', $date);
        $builder->andWhere('bucket.end_date >= :act_date')->setParameter('act_date', $date);
        $builder->groupBy('bucket.limit_val');
        $builder->setMaxResults(1);
        $builder->orderBy('max_limit', 'DESC');
        try {
            $matching = $builder->getQuery()->getOneOrNullResult();
            # var_dump($matching[0]);
            return $matching[0];
        } catch (NonUniqueResultException $e) {
            return null;
        }

    }

    /*
    public function findOneBySomeField($value): ?Bucket
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
