<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

        /**
         * @return Comment[] Returns an array of Comment objects
         */
        public function findCommentsByArticle(int $articleId): array //findCommentsByArticle method is part of the CommentRepository class and is used to fetch comments associated with a specific article
        {
            return $this->createQueryBuilder('c')  //This line creates a query builder object, which is a way to build a database query in Doctrine
                ->andWhere('c.article = :articleId') //c is an alias for the Comment entity in this query. Using aliases is necessary!
                ->setParameter('articleId', $articleId)
                ->orderBy('c.id', 'ASC')
                ->setMaxResults(50)
                ->getQuery()
                ->getResult()
            ;
        }

    }   
              
        /* THIS IS A SEARCH OF COMMENT BY ID BUT THE DELETE FORM SHOULD NOT NEED IT ??? */
        
       /* public function findCommentWithCustomQuery(int $id): ?Comment
        {
            return $this->createQueryBuilder('c')
                ->where('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult()
            ;
        } 
  
 
            /* 

            if ($comment && $comment->getUser() === $user) { //means: comment exists, and verifies that the User associated with the comment is the same as the currently logged-in
                $this->_em->remove($comment);
                $this->_em->flush();

                return true;
            }

            return false;
        } */

