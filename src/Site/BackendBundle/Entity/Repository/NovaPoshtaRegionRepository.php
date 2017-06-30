<?php

namespace Site\BackendBundle\Entity\Repository;

/**
 * NovaPoshtaRegionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NovaPoshtaRegionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllIndexByHref(){
        $q = $this->createQueryBuilder('npr','npr.ref')
            ->select('npr.description, npr.ref')
            ->getQuery()
            ->getArrayResult();
        $arr=[];
        if(count($q)){
            $arr['form.empty.data']=0;
            foreach($q as $item){
                $arr[$item['description']]=$item['ref'];
            }
        }
        return $arr;
    }
}
