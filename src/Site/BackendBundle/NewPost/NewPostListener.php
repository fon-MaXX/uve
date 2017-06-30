<?php
namespace Site\BackendBundle\NewPost;
use Site\BackendBundle\Entity\NovaPoshtaData;
use Site\BackendBundle\Entity\NovaPoshtaRegion;
use Site\BackendBundle\Entity\Order;
use Symfony\Component\DependencyInjection\Container;

class NewPostListener{
    private $service;
    private $em;
    private $uow;
    public function __construct($service)
    {
        $this->service=$service;
    }
    public function setUow($uow){
        $this->uow=$uow;
    }
    public function setEm($em){
        $this->em=$em;
    }
    public function updateData($entity){
        if(!$entity->getRegionHref()){
            return false;
        }
        $region = $this->em->getRepository('SiteBackendBundle:NovaPoshtaRegion')->findOneBy([
            'ref'=>$entity->getRegionHref()
        ]);
        if(!$region){
            return false;
        }
        $entity->setRegionName($region->getDescription());
        if($entity->getCityHref()){
           $entity = $this->setCity($entity,$region);
        }
        $classMetadata = $this->em->getClassMetadata('Site\BackendBundle\Entity\NovaPoshtaData');
        $this->uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
    }
    private function setCity($entity,$region){
        $cities = $this->service->getCities($region);
        if($cities&&count($cities)){
            foreach($cities as $city){
                if(
                    isset($city->ref)&&
                    $city->ref==$entity->getCityHref()&&
                    isset($city->description)
                ){
                    $entity->setCityName($city->description);
                    $this->em->persist($entity);

                    if($entity->getWarehouseHref()){
                        $entity = $this->setWarehouse($entity,$city);
                    }
                }
            }
        }
        return $entity;
    }
    private function setWarehouse($entity,$city){
        $warehouses = $this->service->getWarehouses($city);
        if($warehouses&&count($warehouses)){
            foreach($warehouses as $warehouse){
                if(
                    isset($warehouse->ref)&&
                    $warehouse->ref==$entity->getWarehouseHref()&&
                    isset($warehouse->description)
                ){
                    $entity->setWarehouseName($warehouse->description);
                    $this->em->persist($entity);
                }
            }
        }
        return $entity;
    }
}