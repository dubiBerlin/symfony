<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    public function findAllPostByUser($user)
    {
      $qb =  $this->createQueryBuilder('p'); 
      $qb->select("p.title, p.id, p.message, p.image, p.created_at")
          ->where("p.user = :user")
          ->setParameter("user",$user);

      return $qb->getQuery()->getResult();
      
    }

    public function findPostWithCategory(int $postId)
    {
      $qb =  $this->createQueryBuilder('p'); // p für Post
      $qb->select("p.title")
          ->addSelect("p.id as post_id")
          ->addSelect("p.image as image")
          ->addSelect("p.message as message")
          ->addSelect("c.name")
          ->addSelect("c.id as category_id")
          ->innerJoin("p.category","c")
          ->where("p.id = :id")
          ->setParameter("id",$postId);

      return $qb->getQuery()->getResult();
      
    }

    public function countUserPosts($user){
      $qb =  $this->createQueryBuilder('p'); // p für Post
      return $qb->select('count(p.id)')
            ->where("p.user = :user")
            ->setParameter("user",$user)
            ->getQuery()
            ->getSingleScalarResult();
    }


    // /**
    //  * @return Post[] Returns an array of Post objects
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
    public function findOneBySomeField($value): ?Post
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
