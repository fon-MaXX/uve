<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/28/2016
 * Time: 02:18 PM
 */
namespace Site\BackendBundle\EventListeners;

class ProductListener{
    private $em;
    private $uow;
    public function setUow($uow){
        $this->uow=$uow;
    }
    public function setEm($em){
        $this->em=$em;
    }
    public function MakeChanges($entity)
    {
        ($entity->getSharePrice())?$entity->setFilterPrice($entity->getSharePrice()):$entity->setFilterPrice($entity->getPrice());
        $classMetadata = $this->em->getClassMetadata('Site\BackendBundle\Entity\Product');
        $this->uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
        if(count($sets=$entity->getSets())){
            foreach($sets as $set){
                $this->recalculateSetPrice($set);
            }
        }
    }
    public function makeDeleteChanges($entity){
        if(count($sets=$entity->getSets())){
            foreach($sets as $set){
                $this->recalculateSetPrice($set,$entity);
            }
        }
    }
    private function recalculateSetPrice($entity,$product=null){
        if(count($products=$entity->getProducts())){
            $price=0;
            foreach($products as $item){
                $price+=($item->getSharePrice())?$item->getSharePrice():$item->getPrice();
            }
            ($product)?$price-=$product->getFilterPrice():'';
            $entity->setFilterPrice($price);
            $classMetadata = $this->em->getClassMetadata('Site\BackendBundle\Entity\Set');
            $this->uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
        }
    }
}