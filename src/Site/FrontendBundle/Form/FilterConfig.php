<?php

namespace Site\FrontendBundle\Form;

class FilterConfig{
    private $price=10000;
    private $insertionColors=[
        1=>"бесцветный",
        2=>"голубой",
        3=>"синий",
        4=>"зеленый",
        5=>"красный",
        6=>"черный",
        7=>"лаванда",
        8=>"шампань",
        9=>"фиолетовый",
        10=>"оливковый"
    ];
    private $insertionType=[
        1=>"Фианит (куб.цирконий)",
        2=>"Жемчуг",
        3=>"Без вставки"
    ];
    private $state=[
        1=>'В наличии',
        2=>'Под заказ'
    ];
    private $theme = [
        'наборы'=>[
            1=>"Наборы с центральным камней",
            2=>"Наборы с россыпью камней",
            3=>'Наборы без камней',
            ],
        'кольца'=>[
            1=>'Детская тематика',
            2=>'Кольца с россыпью камней',
            3=>'Кольца с центральным камнем',
            4=>'Кольца - короны',
            5=>'Кольца для предложений',
            6=>'Двойные кольца',
            7=>'Парные кольца'
        ],
        'серьги'=>[
            1=>'Серьги с центральным камнем',
            2=>'Серьги с россыпью камней',
            3=>'Серьги с жемчугом',
            4=>'Серьги без камней'
        ],
        'подвесы'=>[
            1=>"Подвесы в виде сердца",
            2=>"Тема любви",
            3=>"Детская тематика",
            4=>"Тема природы",
            5=>"Подвесы с камнями",
            6=>"Подвесы без камней"
        ],
        'аксессуары'=>[],
        'пирсинг'=>[],
        'цепи и браслеты'=>[],
        'пандора'=>[],
        'золото'=>[]
    ];
    private $chainSizes=[
        1=>"40",
        2=>"45",
        3=>"50",
        4=>"55",
        5=>"60",
        6=>"65"
    ];
    private $filters=[
        'наборы'=>['price','theme','state','insertionType'],
        'кольца'=>['price','theme','state','insertionType'],
        'серьги'=>['price','theme','state','insertionType'],
        'подвесы'=>['price','theme','state','insertionType'],
        'аксессуары'=>['price','theme','state','insertionType'],
        'пирсинг'=>['price','theme','state','insertionType'],
        'цепи и браслеты'=>['price','theme','state','insertionType','chainSizes'],
        'пандора'=>['price','theme','state','insertionType'],
        'золото'=>['price','theme','state','insertionType'],
    ];
    public function getFilterConfig($title){
        if(!$title||(!isset($this->filters[$title]))||(!count($this->filters[$title]))){
          return false;
        }
        $result=[];
        foreach($this->filters[$title] as $value){
            $item = $this->$value;
            if($value=='theme'){
                (count($item[$title]))?$result[$value]=$item[$title]:'';
            }
            else{
                $result[$value] = $item;
            }
        }
        return $result;
    }
}