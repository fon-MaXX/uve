<?php
/**
 * Created by PhpStorm.
 * User: MaXX
 * Date: 3/17/17
 * Time: 15:13
 */
namespace Site\FrontendBundle\Services;
class BreadcrumbsGenerator{
    /**
     * Routes for breadcrumbs generator
     *
     * @var array
     */
    private $menu = [
        'main'=>'site_frontend_homepage',
        'category'=>'site_frontend_category',
        'sub_category'=>'site_frontend_sub_category',
        'product'=>'site_frontend_product_show',
        'set'=>'site_frontend_set_list',
        'news'=>'site_frontend_news_list',
        'comparison_list'=>'site_frontend_comparing_list'
    ];
    private $router;
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * Generates array for breadcrumbs menu with parameters url,title
     * All available routes are set in $this->menu parameter
     * $arr should be an array like ['category']=>['title'=>'Some Text','parameters'=>['par1'=>'asd']]
     *
     * @param array $arr
     * @return array|bool
     * @throws \Exception
     */
    public function generateMenu(array $arr){
        if(count($arr)<1){
            return false;
        }
        $result = [];
        foreach($arr as $key=>$value){
            if($key!='last'){
                if(!isset($this->menu[$key])||!isset($value['parameters'])||!isset($value['title'])){
                    throw new \Exception('Отсутствует маршрут для ключа '.$key.' или не заданы параметры title,parameters ');
                }
                $route =$this->menu[$key];
                $temp=[];
                $temp['url']=$this->router->generate($route,$value['parameters']);
                $temp['title']=$value['title'];
                $result[]=$temp;
            }
        }
        $result[]['title'] = (isset($arr['last']))?$arr['last']:'';
        return $result;
    }
}