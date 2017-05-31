<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 3/1/2016
 * Time: 12:50 PM
 */
namespace Site\UploadBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

class FileUpdateListener
{
    private $elements =[
        'set'=>['poster'],
        'product'=>['poster']
    ];
    public function __construct(\Site\UploadBundle\Services\FileHandler $fileHandler,$webDir,$rootDir)
    {
        $this->fileHandler = $fileHandler;
        $this->webDir = $webDir;
        $this->rootDir=realpath($rootDir);
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $shortClass=explode("\\",get_class($entity));
        $shortClass=end($shortClass);

        if(isset($this->elements[$shortClass])){
            $this->handleFiles($this->elements[$shortClass],$args);
        }
        return;
    }
    private function handleFiles($arr,$args){
        if(count($arr)<1){
            return;
        }
        foreach($arr as $field) {
            if ($args->hasChangedField($field)) {
                $this->removeFiles($args->getOldValue($field));
            }
        }
    }
    private function removeFiles($str)
    {
        $arr=json_decode($str,true);
        if(($arr)&&isset($arr["default_file"])&&!empty($arr["default_file"])){
            foreach($arr as $value)
            {
                if(!$this->checkForTest($value)){
                    $file = $this->rootDir.'/../'.$this->webDir.$value;
                    if (!is_dir($file)&&file_exists($file)){
                        @unlink($file);
                    }
                }
            }
        }
    }
    /**
     * prevent delete of test files
     *
     * @param $value
     * @return bool
     */
    private function checkForTest($value){
        if(substr($value,0,5)=='/test'){
            return true;
        }
        return false;
    }
}