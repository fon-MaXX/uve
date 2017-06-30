<?php
/**
 * Created by PhpStorm.
 * User: MaXX
 * Date: 3/17/17
 * Time: 15:13
 */
namespace Site\FrontendBundle\Services;
class NumberInCart{
    private $session=[];
    public function __construct($session){
        $this->session=$session;
    }
    public function getItemsNumber($sessionName){

        $session = $this->session->get($sessionName);
        $list = ($session)?json_decode($session,true):null;
        $number = 0;
        if (json_last_error() === JSON_ERROR_NONE && $list) {
            $number = (is_array($list))?count($list):0;
        }
        return $number;
    }
}