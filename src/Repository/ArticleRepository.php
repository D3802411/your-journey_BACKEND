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

    
    public function searchArticles($data): array
    {
        $qb = $this->createQueryBuilder('article');

        // If place is provided, filter by place
        if (!empty($data['place'])) {
          $qb->andWhere('article.place LIKE :place')
             ->setParameter('place', '%' . $data['place'] . '%');
            }
        
        // If city is provided, filter by city, etc
        if (!empty($data['city'])) {
          $qb->andWhere('article.city LIKE :city')
             ->setParameter('city', '%' . $data['city'] . '%');
        }

        if (!empty($data['country'])) {
          $qb->andWhere('article.country LIKE :country')
                     ->setParameter('country', '%' . $data['country'] . '%');
        }
        if (!empty($data['attraction'])) {
          $qb->andWhere('article.attraction LIKE :attraction')
                     ->setParameter('attraction', '%' . $data['attraction'] . '%');
        }
        if (!empty($data['activity'])) {
          $qb->andWhere('article.activity LIKE :activity')
                     ->setParameter('activity', '%' . $data['activity'] . '%');
        }

        // If title is provided, filter by title
        if (!empty($data['title'])) {
            $qb->andWhere('article.title LIKE :title')
               ->setParameter('title', '%' . $data['title'] . '%');
        }

       // if (!empty($data['user'])) {
       //   $qb->andWhere('article.username LIKE :username')
       //      ->setParameter('username', '%' . $data['username'] . '%');
      //}

      // You can add more conditions here if needed, like ordering
        $qb->orderBy('article.id', 'ASC'); 
      //debug logging here
        $query = $qb->getQuery();
        $sql = $query->getSQL();
        $params = $query->getParameters();
        dump($sql, $params);

        return $qb->getQuery()->getResult();
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