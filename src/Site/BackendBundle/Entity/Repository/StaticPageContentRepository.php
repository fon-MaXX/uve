<?php

namespace Site\BackendBundle\Entity\Repository;

/**
 * StaticPageContentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StaticPageContentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getStaticContentForPage($title){
        return $this->createQueryBuilder('scp','scp.linkname')
            ->leftJoin('scp.staticPage','sp')
            ->where('sp.title = :title')
            ->setParameter('title',$title)
            ->getQuery()
            ->getResult();
    }
}
