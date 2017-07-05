<?php
/**
 * Created by PhpStorm.
 * User: MaXX
 * Date: 3/17/17
 * Time: 15:13
 */
namespace Site\FrontendBundle\Services;
use Site\BackendBundle\Entity\Order;

class NumberInCart{
    private $session=[];
    public function __construct($session){
        $this->session=$session;
    }
    public function getItemsNumber($sessionName){
        $session = $this->session->get($sessionName);
        $order = ($session)?unserialize($session):new Order();
        $number = 0 + count($order->getOrderHasProducts()) + count($order->getOrderHasSets());
        return $number;
    }
}