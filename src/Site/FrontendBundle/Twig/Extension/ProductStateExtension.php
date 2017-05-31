<?php
namespace Site\FrontendBundle\Twig\Extension;

class ProductStateExtension extends \Twig_Extension
{
    private $states = [
        'вналичии'=>'in_stock',
        'подзаказ'=>'in_order'
    ];
    public function getName()
    {
        return 'product_state.extension';
    }
    public function getFilters() {
        return [
            'product_state'   => new \Twig_SimpleFilter('product_state', array($this, 'productState')),
        ];
    }
    public function productState($str) {
        $str = mb_strtolower(str_replace(' ', '', $str),'UTF-8');
        return (isset($this->states[$str]))?$this->states[$str]:'';
    }
}