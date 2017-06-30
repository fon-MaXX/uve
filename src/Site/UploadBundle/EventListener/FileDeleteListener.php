<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 3/1/2016
 * Time: 12:50 PM
 */
namespace Site\UploadBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Site\UploadBundle\Services\FileHandler;

class FileDeleteListener
{
    private $elements = [
        'Product'=>['poster'],
        'ProductGallery'=>['image'],
        'Set'=>['poster'],
        'SetGallery'=>['image'],
        'Slider'=>['image'],
        'News'=>['poster'],
    ];
    public function __construct(FileHandler $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $shortClass=explode("\\",get_class($entity));
        $shortClass=end($shortClass);
        if(isset($this->elements[$shortClass])){
            $this->handleFiles($this->elements[$shortClass],$entity);
        }
        return;
    }
    private function handleFiles($arr,$entity)
    {
        $option=array_values($arr)[0];
        $getter='get'.ucfirst($option);
        $data = @json_decode($entity->$getter(),true);
        if(@$data['default_file']){
            $path=array_slice(explode('/',$data['default_file']),1,-1);
            $str=null;
            foreach($path as $p){
                $str.='/'.$p;
            }
            $this->fileHandler->clearDirectory($str,true);
        }
    }
}