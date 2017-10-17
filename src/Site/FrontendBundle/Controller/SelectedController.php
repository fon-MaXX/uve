<?php

namespace Site\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SelectedController extends Controller
{

    private $selectedSession = 'selected_session';
    private $comparisonSession = 'comparison_session';
    public function comparisonListAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'Главная'
            ],
            'last'=>'Список сравнения'
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $items = $this->getItemsFromSession($this->comparisonSession);
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>'comparing_list'
        ]);
        return $this->render('@SiteFrontend/Selected/comparison_list.html.twig',[
            'items'=>$items,
            'breadcrumbs'=>$menu,
            'seo'=>$seo
        ]);
    }
    public function comparisonShowAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'Главная'
            ],
//            'comparison_list'=>[
//                'parameters'=>[],
//                'title'=>'Список сравнения'
//            ],
            'last'=>'Сравнение'
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $items = $this->getItemsFromSession($this->comparisonSession);
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>'comparing_show'
        ]);
        return $this->render('@SiteFrontend/Selected/comparison_show.html.twig',[
            'items'=>$items,
            'breadcrumbs'=>$menu,
            'seo'=>$seo
        ]);
    }
    public function selectedListAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'Главная'
            ],
            'last'=>'Избранные'
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $items=$this->getItemsFromSession($this->selectedSession);
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>'selected'
        ]);
        return $this->render('@SiteFrontend/Selected/selected_list.html.twig',[
            'items'=>$items,
            'breadcrumbs'=>$menu,
            'seo'=>$seo
        ]);
    }
    public function addProductAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
        $number = $this->addItemToSession($entity,'product',$this->selectedSession);
        $result=[
            'number'=>$number,
            'type'=>'selected'
        ];
        return new Response(json_encode($result));
    }
    public function addSetAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Set')->findOneBySlug($slug);
        $number = $this->addItemToSession($entity,'set',$this->selectedSession);
        $result=[
            'number'=>$number,
            'type'=>'selected'
        ];
        return new Response(json_encode($result));
    }
    public function addProductToComparingAction(Request $request, $slug)
    {
        $result=[
            'success'=>false
        ];
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
        $number = $this->addItemToSession($entity,'product',$this->comparisonSession);
        if($number)
        {
            $result=[
                'number'=>$number,
                'type'=>'comparing'
            ];
        }
        return new Response(json_encode($result));
    }
    public function addSetToComparingAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Set')->findOneBySlug($slug);
        $number=$this->addItemToSession($entity,'set',$this->comparisonSession);
        $result=[
            'number'=>$number,
            'type'=>'comparing'
        ];
        return new Response(json_encode($result));
    }
    public function removeProductFromSelectedAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
        $number = $this->removeItemFromSession($entity,'product',$this->selectedSession);
        $result=[
            'number'=>$number,
            'type'=>'selected'
        ];
        return new Response(json_encode($result));
    }
    public function removeSetFromSelectedAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Set')->findOneBySlug($slug);
        $number = $this->removeItemFromSession($entity,'set',$this->selectedSession);
        $result=[
            'number'=>$number,
            'type'=>'selected'
        ];
        return new Response(json_encode($result));
    }
    public function removeProductFromComparingAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
        $number = $this->removeItemFromSession($entity,'product',$this->comparisonSession);
        $result=[
            'number'=>$number,
            'type'=>'comparing'
        ];
        return new Response(json_encode($result));
    }
    public function removeSetFromComparingAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Set')->findOneBySlug($slug);
        $number  = $this->removeItemFromSession($entity,'set',$this->comparisonSession);
        $result=[
            'number'=>$number,
            'type'=>'comparing'
        ];
        return new Response(json_encode($result));
    }
    private function removeItemFromSession($entity,$type,$sessionName){
        $session = $this->get('session')->get($sessionName);
        $list = ($session)?json_decode($session,true):[];
        if ((json_last_error() === JSON_ERROR_NONE)&&($entity)) {
            $keys = array_keys($list);
            $key = $entity->getId().'-'.$type;
            if(in_array($key,$keys)){
                unset($list[$key]);
                $session  = json_encode($list);
                $this->get('session')->set($sessionName,$session);
            }
        }
        return count($list);
    }
    private function addItemToSession($entity,$type,$sessionName)
    {
        $session = $this->get('session')->get($sessionName);
        $list = ($session)?json_decode($session,true):[];
        if ((json_last_error() === JSON_ERROR_NONE)&&($entity)) {
            $keys = (is_array($list))?array_keys($list):[];
            $key = $entity->getId().'-'.$type;
            if(!in_array($key,$keys)){
                $list[$key]=$entity->getId();
                $session  = json_encode($list);
                $this->get('session')->set($sessionName,$session);
            }
        }
        return count($list);
    }
    private function getItemsFromSession($sessionName)
    {
        $session = $this->get('session')->get($sessionName);
        $list = ($session)?json_decode($session,true):[];
        if(!count($list)){
            return false;
        }
        if (!(json_last_error() === JSON_ERROR_NONE)){
            return false;
        }
        $productIds=[];
        $setIds=[];
        foreach($list as $key=>$item){
            $type = explode('-',$key);
            if($type[1]=='product'){
                $productIds[]=$item;
            }
            elseif($type[1]=='set'){
                $setIds[] = $item;
            }
        }
        $em= $this->getDoctrine()->getManager();
        $sets = (count($setIds))?$em->getRepository('SiteBackendBundle:Set')->getByAndIndexIds($setIds):[];
        $products = (count($productIds))?$em->getRepository('SiteBackendBundle:Product')->getByAndIndexIds($productIds):[];
        $result=[];
        foreach($list as $key=>$item){
            $type = explode('-',$key);
            if($type[1]=='product'){
                if(isset($products[$item])){
                    $result[]=[
                        'type'=>'product',
                        'entity'=>$products[$item]
                    ];
                }
            }
            elseif($type[1]=='set'){
                if(isset($sets[$item])){
                    $result[]=[
                        'type'=>'set',
                        'entity'=>$sets[$item]
                    ];
                }
            }
        }
        return $result;
    }
}
