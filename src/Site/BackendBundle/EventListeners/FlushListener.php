<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/28/2016
 * Time: 02:18 PM
 */
namespace Site\BackendBundle\EventListeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Site\BackendBundle\Entity\Product;
use Site\BackendBundle\Entity\Set;

class FlushListener{
    private $productListener;
    private $setListener;
    private $newpostListener;
    public function __construct($productListener,$setListener,$newpostListener)
    {
        $this->productListener = $productListener;
        $this->setListener = $setListener;
        $this->newpostListn=$newpostListener;
    }
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        $this->productListener->setEm($em);
        $this->productListener->setUow($uow);
        $this->setListener->setEm($em);
        $this->setListener->setUow($uow);
        $this->newpostListn->setEm($em);
        $this->newpostListn->setUow($uow);

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $class=explode("\\",get_class($entity));
            $class=end($class);
            switch ($class) {
                case "Product":
                    $this->productListener->makeChanges($entity);
                    break;
                case "Set":
                    $this->setListener->makeChanges($entity);
                    break;
                case "NovaPoshtaData":
                    $this->newpostListn->updateData($entity);
                    break;
            }
        }
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $class=explode("\\",get_class($entity));
            $class=end($class);
            switch ($class) {
                case "Product":
                    $this->productListener->makeChanges($entity);
                    break;
                case "Set":
                    $this->setListener->makeChanges($entity);
                    break;
                case "NovaPoshtaData":
                    $this->newpostListn->updateData($entity);
                    break;
            }
        }
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $class=explode("\\",get_class($entity));
            $class=end($class);
            switch ($class) {
                case "Product":
                    $this->productListener->makeDeleteChanges($entity);
                    break;
            }
        }
//
//        foreach ($uow->getScheduledCollectionDeletions() as $col) {
//
//        }
//
//        foreach ($uow->getScheduledCollectionUpdates() as $col) {
//
//        }

    }

}