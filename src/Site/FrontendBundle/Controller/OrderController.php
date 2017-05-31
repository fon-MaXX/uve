<?php

namespace Site\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Site\BackendBundle\Entity\Order;
use Site\BackendBundle\Entity\OrderHasProduct;
use Site\BackendBundle\Entity\OrderHasSet;
use Site\BackendBundle\Entity\OrderHasSetComponent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\FrontendBundle\Form\ProductFilterType;
use Site\FrontendBundle\Form\OrderType;

class OrderController extends Controller
{
    private $cartSession = 'cart_session';
    private $previousUrl = 'previous-url';
    public function createAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $previousUrl  = $this->getRefererUrl($request);
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'главная'
            ],
            'last'=>"Корзина"
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);

        $order = $this->getOrderObject();
        $form = $this->createForm(OrderType::class,$order,[
            'action'=>$this->get('router')->generate('site_frontend_order_create',[]),
        ]);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $object = $form->getData();
                if(count($object->getOrderHasProducts())||count($object->getOrderHasSets())){
                    $price = $this->countOrderSum($object);
                    $object->setPrice($price);
                    $object->setState('new');
                    $message = 'Заказ успешно оформлен, мы свяжемся с вами в ближайшее время...';
                    $em->persist($object);
                    $em->flush();
                    $this->clearSession($this->cartSession);
                }
                else{
                    $message = 'Заказ не оформлен, так как список товаров пуст';
                }
                return $this->render('SiteFrontendBundle:Order:success.html.twig',[
                    'breadcrumbs'=>$menu,
                    'refererUrl'=> $previousUrl,
                    'message'=>$message
                ]);
            }
        }
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>'order'
        ]);
        return $this->render('SiteFrontendBundle:Order:create.html.twig',[
            'breadcrumbs'=>$menu,
            'refererUrl'=> $previousUrl,
            'form'=>$form->createView(),
            'seo'=>$seo
        ]);
    }

    /**
     * method to get number of elements in user cart
     *
     */
    public function getItemsNumberAction(Request $request){

        $itemsNumber = $this->get('fonmaxx.cart.items.number')->getItemsNumber($this->cartSession);
        $result = json_encode([
            'success'=>true,
            'number'=>$itemsNumber
        ]);
        return new JsonResponse($result);
    }
    public function addProductAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
        $this->addItemToSession($entity,'product',$this->cartSession);
        $order = $this->getOrderObject();
        $form = $this->createForm(OrderType::class,$order,[
            'action'=>$this->get('router')->generate('site_frontend_order_create',[]),
        ]);
        return $this->render('SiteFrontendBundle:Order:_ajax_cart.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    public function removeProductAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
        $this->removeItemFromSession($entity,'product',$this->cartSession);
        return new Response('success');
    }
    public function addSetAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Set')->findOneBySlug($slug);
        $this->addItemToSession($entity,'set',$this->cartSession);
        $order = $this->getOrderObject();
        $form = $this->createForm(OrderType::class,$order,[
            'action'=>$this->get('router')->generate('site_frontend_order_create',[]),
        ]);
        return $this->render('SiteFrontendBundle:Order:_ajax_cart.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    public function removeSetAction(Request $request, $slug)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Set')->findOneBySlug($slug);
        $this->removeItemFromSession($entity,'set',$this->cartSession);
        return new Response('success');
    }
    public function getAjaxCartAction(Request $request)
    {
        $order = $this->getOrderObject();
        $form = $this->createForm(OrderType::class,$order,[
            'action'=>$this->get('router')->generate('site_frontend_order_create',[]),
        ]);
        return $this->render('SiteFrontendBundle:Order:_ajax_cart.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * get referrer page url for keep shopping button
     *
     * if we`r staying on current page , referer is taking from session
     *
     * @return array|mixed|string
     */
    private function getRefererUrl(Request $request){
        $previousUrl = null;
        $previousUrl = $request->headers->get('referer');
        $url = $request->getSession()->get($this->previousUrl);
        if($previousUrl&&($request->getUri()!=$previousUrl)){
            $request->getSession()->set($this->previousUrl, $previousUrl);
            $url = $previousUrl;
        }
        return $url;
    }
    private function removeItemFromSession($entity,$type,$sessionName){
        $session = $this->get('session')->get($sessionName);
        $list = json_decode($session,true);
        if ((json_last_error() === JSON_ERROR_NONE)&&($entity)) {
            $keys = array_keys($list);
            $key = $entity->getId().'-'.$type;
            if(in_array($key,$keys)){
                unset($list[$key]);
                $session  = json_encode($list);
                $this->get('session')->set($sessionName,$session);
            }
        }
    }
    private function addItemToSession($entity,$type,$sessionName)
    {
        $session = $this->get('session')->get($sessionName);
        $list = json_decode($session,true);
        if ((json_last_error() === JSON_ERROR_NONE)&&($entity)) {
            $keys = (is_array($list))?array_keys($list):[];
            $key = $entity->getId().'-'.$type;
            if(!in_array($key,$keys)){
                $list[$key]=$entity->getId();
                $session  = json_encode($list);
                $this->get('session')->set($sessionName,$session);
            }
        }
    }
    private function getOrderObject(){
        $em=$this->getDoctrine()->getManager();
        $session = $this->get('session')->get($this->cartSession);
        $list = json_decode($session,true);
        $order = new Order();
        if ((json_last_error() === JSON_ERROR_NONE)&&count($list)) {
            $productIds=[];
            $setIds=[];
            foreach($list as $key=>$item){
                $type = explode('-',$key);
                if($type[1]=='set'){
                    $setIds[]=$item;
                }
                else{
                    $productIds[]=$item;
                }
            }
            $products = $em->getRepository('SiteBackendBundle:Product')->getByAndIndexIds($productIds);
            $sets = $em->getRepository('SiteBackendBundle:Set')->getByAndIndexIds($setIds);
            $order = $this->addProducts($order,$products);
            $order = $this->addSets($order,$sets);
        }
        return $order;
    }
    private function addProducts($order,$products){
        $em = $this->getDoctrine()->getManager();
        if(count($products)){
            foreach($products as $item){
                $orderHasProduct = new OrderHasProduct();
                $orderHasProduct->setProduct($item);
                $order->addOrderHasProduct($orderHasProduct);
                $em->persist($order);
            }
        }
        return $order;
    }
    private function addSets($order,$sets){
        $em= $this->getDoctrine()->getManager();
        if(count($sets)){
            foreach($sets as $item){
                $orderHasSet = new OrderHasSet();
                $orderHasSet->setSet($item);
                $components = $item->getProducts();
                foreach($components as $component){
                    $orderHasSetComponent = new OrderHasSetComponent();
                    $orderHasSetComponent->setProduct($component);
                    $orderHasSet->addOrderHasSetComponent($orderHasSetComponent);
                    $em->persist($orderHasSet);
                }
                $order->addOrderHasSet($orderHasSet);
                $em->persist($order);
            }
        }
        return $order;
    }
    private function clearSession($sessionName){
        $this->get('session')->remove($sessionName);
    }
    private function countOrderSum($object){
        $sum=0;
        if(count($products = $object->getOrderHasProducts())){
            foreach($products as $item){
                $sum+=($item->getProduct()->getSharePrice())?$item->getProduct()->getSharePrice()*$item->getNumber():$item->getProduct()->getPrice()*$item->getNumber();
            }
        }
        if(count($sets = $object->getOrderHasSets())){
            foreach($sets as $item){
                $number = $item->getNumber();
                if(count($components = $item->getOrderHasSetComponents())){
                    $tempSum=0;
                    foreach($components as $component){
                        if($component->getPresence()){
                            $tempSum+=($component->getProduct()->getSharePrice())?$component->getProduct()->getSharePrice():$component->getProduct()->getPrice();
                        }
                    }
                }
                $sum+=$tempSum*$number;
            }
        }
        return $sum;
    }
}
