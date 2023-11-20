<?php

namespace App\Repository;

use App\Entity\VideoEncode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoEncode>
 *
 * @method VideoEncode|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoEncode|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoEncode[]    findAll()
 * @method VideoEncode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoEncodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoEncode::class);
    }

//    /**
//     * @return VideoEncode[] Returns an array of VideoEncode objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VideoEncode
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
