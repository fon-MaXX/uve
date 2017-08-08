<?php
namespace Site\FrontendBundle\Twig\Extension;

class RatingExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'rating.extension';
    }
    public function getFilters() {
        return [
            'rating'   => new \Twig_SimpleFilter('rating', array($this, 'rating')),
        ];
    }
    public function rating($index) {
        $htmlText = '';
        switch($index){
            case 1:
                $htmlText = "<div>1</div><div>Балл</div>";
                break;
            case 2:
                $htmlText = "<div>2</div><div>Балла</div>";
                break;
            case 3:
                $htmlText = "<div>3</div><div>Балла</div>";
                break;
            case 4:
                $htmlText = "<div>4</div><div>Балла</div>";
                break;
            case 5:
                $htmlText = "<div>5</div><div>Баллов</div>";
                break;
        }
        return  $htmlText;
    }
}