<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }


  //  * @return Article[] Returns an array of Article objects


// @param string|null $query
// @return Article[]

    //findBySearchQuery: is a custom, reusable method that uses method createQueryBuilder internally to encapsulate search logic
    public function findBySearchQuery(array $query): array
    {
      $queryBuilder = $this->createQueryBuilder('a'); // a is for article, it's OK to give "a"

        // If place is provided, filter by place
        if (!empty($query['place'])) {
          $queryBuilder
            ->andWhere('a.place LIKE :place')
            ->setParameter('place', '%' . $query['place'] . '%');
            }
        
        // If city is provided, filter by city, etc
        if (!empty($query['city'])) {
          $queryBuilder
            ->andWhere('a.city LIKE :city')
            ->setParameter('city', '%' . $query['city'] . '%');
        }

        if (!empty($query['country'])) {
          $queryBuilder
            ->andWhere('a.country LIKE :country')
            ->setParameter('country', '%' . $query['country'] . '%');
        }
        if (!empty($query['attraction'])) {
          $queryBuilder
            ->andWhere('a.attraction LIKE :attraction')
            ->setParameter('attraction', '%' . $query['attraction'] . '%');
        }

        if (!empty($query['activity'])) {
          $queryBuilder
            ->andWhere('a.activity LIKE :activity')
            ->setParameter('activity', '%' . $query['activity'] . '%');
        }

        // If title is provided, filter by title
        if (!empty($query['title'])) {
          $queryBuilder
            ->andWhere('a.title LIKE :title')
            ->setParameter('title', '%' . $query['title'] . '%');
        }

        return $queryBuilder     
          ->orderBy('a.id', 'DESC') // You can add more conditions here if needed, like ordering
          ->getQuery()
          ->getResult();
    }
}
    
    
    
    //public function findByCity($value): array
    //   {
    //    return $this->createQueryBuilder('article')
    //          ->andWhere('article.city = :val')
    //          ->setParameter('val', $value)
    //          ->setMaxResults(50)
    //          ->orderBy('article.id', 'ASC')
    //          ->getQuery()
    //          ->getResult()
    //       ;
    //   }



    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }