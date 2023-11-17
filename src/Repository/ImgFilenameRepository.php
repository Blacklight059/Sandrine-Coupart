<?php

namespace App\Repository;

use App\Entity\ImgFilename;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CVFileName>
 *
 * @method ImgFilename|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImgFilename|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImgFilename[]    findAll()
 * @method ImgFilename[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImgFilenameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImgFilename::class);
    }

//    /**
//     * @return CVFileName[] Returns an array of CVFileName objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CVFileName
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
