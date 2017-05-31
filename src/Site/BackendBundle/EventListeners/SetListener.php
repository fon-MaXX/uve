<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/28/2016
 * Time: 02:18 PM
 */
namespace Site\BackendBundle\EventListeners;

use Doctrine\ORM\Event\OnFlushEventArgs;

class SetListener{
    private $em;
    private $uow;
    public function setUow($uow){
        $this->uow=$uow;
    }
    public function setEm($em){
        $this->em=$em;
    }
    public function MakeChanges($entity){
        if(count($products=$entity->getProducts())){
            $price=0;
            foreach($products as $item){
                $price+=($item->getSharePrice())?$item->getSharePrice():$item->getPrice();
            }
            $entity->setFilterPrice($price);
            $classMetadata = $this->em->getClassMetadata('Site\BackendBundle\Entity\Set');
            $this->uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
        }
    }
}