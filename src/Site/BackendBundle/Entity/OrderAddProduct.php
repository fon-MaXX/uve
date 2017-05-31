<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


class OrderAddProduct
{
    private $category;
    private $products;
    public function __construct()
    {
       $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getCategory(){
        return $this->category;
    }
    public function setCategory($category){
        $this->category = $category;
        return $this;
    }
    public function getProducts(){
        return $this->products;
    }
    public function setProducts($products){
        $this->products = $products;
        return $this;
    }
}