<?php

namespace Site\BackendBundle\Entity\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllByState($state){
        return $this->createQueryBuilder('q')
            ->where('q.state = :state')
            ->setParameter('state',$state)
            ->getQuery()
            ->getResult();
    }
    public function getCommentsByStateAndPath($state,$path){
        return $this->createQueryBuilder('q')
            ->where('q.state = :state')
            ->andWhere('q.pageUrl = :path')
            ->setParameters([
                'state'=>$state,
                'path'=>$path
            ])
            ->getQuery()
            ->getResult();
    }
}
