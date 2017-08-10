<?php
namespace Site\BackendBundle\Twig\Extension;
use Site\BackendBundle\Entity\Order;

class OrderStateExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'order_state.extension';
    }
    public function getFilters() {
        return [
            'order_state'   => new \Twig_SimpleFilter('order_state', array($this, 'orderState')),
        ];
    }
    public function orderState($str) {
        $order = new Order();
        $states = array_flip($order->states);
        $str = mb_strtolower(str_replace(' ', '', $str),'UTF-8');
        $result='';
        if(isset($states[$str])){
            $result = "<span class='state-field ".$str."'>".$states[$str]."</span>";
        }
        return $result;
    }
}