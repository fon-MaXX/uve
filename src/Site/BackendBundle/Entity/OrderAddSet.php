<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


class OrderAddSet
{
    private $sets;
    public function __construct()
    {
       $this->sets = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getSets(){
        return $this->sets;
    }
    public function setSets($sets){
        $this->sets = $sets;
        return $this;
    } 
}