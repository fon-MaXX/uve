<?php

namespace Site\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Site\BackendBundle\Entity\NovaPoshtaData;
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
use Site\FrontendBundle\Form\OrderHasSetType;
use Site\FrontendBundle\Form\OrderHasProductType;

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
//      basic order from session
        $order = $this->getOrderObject();
        //        in a case set_show was submitted
        $order = $this->checkOrderHasSetSubmit($request,$order);
        //        in a case product_show was submitted
        $order = $this->checkOrderHasProductSubmit($request,$order);
        if(!$order)return $this->redirect($previousUrl);
        $form = $this->createForm(OrderType::class,$order,[
            'action'=>$this->get('router')->generate('site_frontend_order_create',[]),
            'is_frontend'=>true,
            'container'=>$this->container
        ]);
        if($request->isMethod('POST')&&$request->request->has($form->getName())){
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
                    $this->sendMail($object);
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
            'container'=>$this->container,
            'is_frontend'=>true,
            'is_ajax'=>true
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
            'container'=>$this->container,
            'is_frontend'=>true,
            'is_ajax'=>true
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
            'container'=>$this->container,
            'is_frontend'=>true,
            'is_ajax'=>true
        ]);
        return $this->render('SiteFrontendBundle:Order:_ajax_cart.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    public function getNovaPoshtaFormAction(Request $request)
    {
        $data = $request->request->all();
//        $data=[
//            "novaPoshtaData"=>[
//                "regionHref"=>"7150812a-9b87-11de-822f-000c2965ae0e",
//                "cityHref"=>"3abce78b-25bd-11e3-83b9-0050568002cf",
//
//            ]
//        ];
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order, [
            'container' => $this->container,
            'is_frontend'=>true

        ]);
        $form->submit($data);
        return $this->render('SiteFrontendBundle:Order:_novaPoshtaDataForm.html.twig', array(
            'form' => $form->createView()
        ));
    }
    public function getUkrPoshtaFormAction(Request $request)
    {
        $data = $request->request->all();
//        $data=[
//            "ukrPoshtaData"=>[
//                "address"=>"text",
//
//            ]
//        ];
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order, [
            'container' => $this->container,
            'is_frontend'=>true

        ]);
        $form->submit($data);
        return $this->render('SiteFrontendBundle:Order:_ukrPoshtaDataForm.html.twig', array(
            'form' => $form->createView()
        ));
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
        $orderCreateUri = $this->get('router')->generate('site_frontend_order_create',[],0);
        $url = $request->getSession()->get($this->previousUrl);
        if(
            $previousUrl&&
            $request->getUri()!=$previousUrl&&
            $previousUrl!=$orderCreateUri
        )
        {
            $request->getSession()->set($this->previousUrl, $previousUrl);
            $url = $previousUrl;
        }
        return $url;
    }
    private function removeItemFromSession($entity,$type,$sessionName){
        $session = $this->get('session')->get($sessionName);
        $list = ($session)?json_decode($session,true):[];
        if ((json_last_error() === JSON_ERROR_NONE)&&($entity)) {
            $keys = array_keys($list);
            $key = $entity->getId() . '-' . $type;
            foreach ($list as $k=>$item) {
                $arr = explode('-',$k);
                $arrKey = (count($arr)>2)?$arr[1].'-'.$arr[2]:null;
                if($arrKey == $key){
                    unset($list[$k]);
                    $session = json_encode($list);
                    $this->get('session')->set($sessionName, $session);
                    return;
                }
            }
        }
    }
    private function addItemToSession($entity,$type,$sessionName)
    {
        $session = $this->get('session')->get($sessionName);
        $list = ($session)?json_decode($session,true):[];
        if ((json_last_error() === JSON_ERROR_NONE)&&($entity)) {
            $key = time().'-'.$entity->getId().'-'.$type;
            $list[$key]=$entity->getId();
            $session  = json_encode($list);
            $this->get('session')->set($sessionName,$session);
        }
    }
    private function getOrderObject(){
        $em=$this->getDoctrine()->getManager();
        $session = $this->get('session')->get($this->cartSession);
        $list = ($session)?json_decode($session,true):[];
        $order = new Order();
        if ((json_last_error() === JSON_ERROR_NONE)&&count($list)) {
            $productIds=[];
            $setIds=[];
            foreach($list as $key=>$item){
                $type = explode('-',$key);
                if($type[2]=='set'){
                    $setIds[]=$item;
                }
                else{
                    $productIds[]=$item;
                }
            }
            $products = $em->getRepository('SiteBackendBundle:Product')->getByAndIndexIds($productIds);
            $sets = $em->getRepository('SiteBackendBundle:Set')->getByAndIndexIds($setIds);
            $order = $this->addProducts($order,$products,$productIds);
            $order = $this->addSets($order,$sets,$setIds);
        }
        return $order;
    }
    private function addProducts($order,$products,$ids){
        $em = $this->getDoctrine()->getManager();
        if(count($products)) {
            foreach ($ids as $id){
                if(isset($products[$id])) {
                    $product = $products[$id];
                    $orderHasProduct = new OrderHasProduct();
                    $orderHasProduct->setProduct($product);
                    $order->addOrderHasProduct($orderHasProduct);
                    $em->persist($order);
                }
            }
        }
        return $order;
    }
    private function addSets($order,$sets,$ids){
        $em= $this->getDoctrine()->getManager();
        if(count($sets)){
            foreach($ids as $id){
                if(isset($sets[$id])){
                    $set = $sets[$id];
                    $orderHasSet = new OrderHasSet();
                    $orderHasSet->setSet($set);
                    $components = $set->getProducts();
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
    private function sendMail($entity){
        return;
        $parameters = $this->container->getParameter('mailer_parameters');
        $message = \Swift_Message::newInstance()
            ->setSubject('Новый заказ ' . '(id:' . $entity->getId() . ')' . ' с сайта "Uvelife.com"')
            ->setFrom($parameters['send_from'])
            ->setBody(
                $this->renderView('SiteFrontendBundle:EMails:_orderManagerMail.html.twig',['entity' => $entity]),
                'text/html'
            );
        foreach ($parameters['send_to'] as $to) {
            $message->setTo($to);
            $this->get('mailer')->send($message);
        }
    }

    /**
     * method to handle submit of set_show
     *
     * we just need to add orderHasSet to current Order to have all states selected in show
     * and add it to session
     *
     * in a case invalid form false order and redirect to referrer
     *
     * @param $request
     * @param $order
     * @return mixed
     */
    private function checkOrderHasSetSubmit($request,$order){
        $em = $this->getDoctrine()->getManager();
        $orderHasSet = $this->getOrderHasSetObject($request);
        if(!$orderHasSet){
            return $order;
        }
        $orderHasSetForm = $this->createForm(OrderHasSetType::class,$orderHasSet,[]);

        if($request->isMethod('POST')&&$request->request->has($orderHasSetForm->getName())){
            $orderHasSetForm->handleRequest($request);
            if($orderHasSetForm->isValid()){
//            we just need to add orderHasSet to current Order to have all states selected in show
//            and add it to session
                if(count($order->getOrderHasSets())){
                    foreach($order->getOrderHasSets() as $item){
                        if($item->getSet()->getId()==$orderHasSet->getSet()->getId()){
                            $order->removeOrderHasSet($item);
                            $em->persist($order);
                        }
                    }
                }
                $order->addOrderHasSet($orderHasSet);
                $em->persist($order);
                $this->addItemToSession($orderHasSet->getSet(),'set',$this->cartSession);
            }
            else{
                return false;
            }
        }
        return $order;
    }
    /**
     * method to handle submit of product_show
     *
     * we just need to add orderHasSet to current Order to have all states selected in show
     * and add it to session
     *
     * in a case invalid form false order and redirect to referrer
     *
     * @param $request
     * @param $order
     * @return mixed
     */
    private function checkOrderHasProductSubmit($request,$order){
        $em = $this->getDoctrine()->getManager();
        $orderHasProduct = $this->getOrderHasProductObject($request);
        if(!$orderHasProduct){
            return $order;
        }
        $orderHasProductForm = $this->createForm(OrderHasProductType::class,$orderHasProduct,[]);
        if($request->isMethod('POST')&&$request->request->has($orderHasProductForm->getName())){
            $orderHasProductForm->handleRequest($request);
            if($orderHasProductForm->isValid()){
                if(count($order->getOrderHasProducts())){
                    foreach($order->getOrderHasProducts() as $item){
                        if($item->getProduct()->getId()==$orderHasProduct->getProduct()->getId()){
                            $order->removeOrderHasProduct($item);
                            $em->persist($order);
                        }
                    }
                }
                $order->addOrderHasProduct($orderHasProduct);
                $em->persist($order);
                $this->addItemToSession($orderHasProduct->getProduct(),'product',$this->cartSession);
            }
            else{
                return false;
            }
        }
        return $order;
    }
    private function getOrderHasProductObject($request)
    {
        $em = $this->getDoctrine()->getManager();
        $slug = $request->query->get('slug', null);
        if ($slug){
            $product = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
            if ($product) {
                $orderHasProduct = new OrderHasProduct();
                $orderHasProduct->setProduct($product);
                $em->persist($orderHasProduct);
                return $orderHasProduct;
            }
        }
        return false;
    }
    private function getOrderHasSetObject($request){
        $em= $this->getDoctrine()->getManager();
        $slug = $request->query->get('slug', null);
        if($slug){
            $set = $em->getRepository('SiteBackendBundle:Set')->findOneBySlug($slug);
            if($set){
                $orderHasSet = new OrderHasSet();
                $orderHasSet->setSet($set);
                $components = $set->getProducts();
                foreach($components as $component){
                    $orderHasSetComponent = new OrderHasSetComponent();
                    $orderHasSetComponent->setProduct($component);
                    $orderHasSet->addOrderHasSetComponent($orderHasSetComponent);
                    $em->persist($orderHasSet);
                }
                return $orderHasSet;
            }
        }
        return false;
    }
}
