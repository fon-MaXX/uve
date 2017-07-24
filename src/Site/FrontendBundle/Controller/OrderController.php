<?php

namespace Site\FrontendBundle\Controller;

use Site\BackendBundle\Entity\Order;
use Site\BackendBundle\Entity\OrderHasProduct;
use Site\BackendBundle\Entity\OrderHasSet;
use Site\BackendBundle\Entity\OrderHasSetComponent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Site\FrontendBundle\Form\OrderType;
use Site\FrontendBundle\Form\OrderHasSetType;
use Site\FrontendBundle\Form\OrderHasProductType;

class OrderController extends Controller
{
//    private $cartSession = 'cart_session';
    private $newCartSession = 'order_cart_session';
    private $previousUrl = 'previous-url';

    public function saveAction(Request $request)
    {
        $order = $this->getNewOrderObject();
        $form = $this->createForm(OrderType::class, $order, [
            'action' => $this->get('router')->generate('site_frontend_order_create', []),
            'container' => $this->container,
            'is_frontend' => true,
            'is_ajax' => true
        ]);
        if ($request->isMethod('POST') && $request->request->has($form->getName())) {
            $form->handleRequest($request);
            $this->addOrderToSession($form->getData(), $this->newCartSession);
            return new Response('success');
        }
        return new Response('error');
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $previousUrl = $this->getRefererUrl($request);
        $arr = [
            'main' => [
                'parameters' => [],
                'title' => 'главная'
            ],
            'last' => "Корзина"
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
//      basic order from session
//        $order = $this->getOrderObject();
        $order = $this->getNewOrderObject();
        //        in a case set_show was submitted
        $order = $this->checkOrderHasSetSubmit($request, $order);
        //        in a case product_show was submitted
        $order = $this->checkOrderHasProductSubmit($request, $order);
        if (!$order) return $this->redirect($previousUrl);
        $form = $this->createForm(OrderType::class, $order, [
            'action' => $this->get('router')->generate('site_frontend_order_create', []),
            'is_frontend' => true,
            'container' => $this->container
        ]);
        if ($request->isMethod('POST') && $request->request->has($form->getName())) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $object = $form->getData();
                if (count($object->getOrderHasProducts()) || count($object->getOrderHasSets())) {
                    $price = $this->countOrderSum($object);
                    $object->setPrice($price);
                    $object->setState('new');
                    $message = 'Заказ успешно оформлен, мы свяжемся с вами в ближайшее время...';
                    $em->persist($object);
                    $em->flush();
                    $this->clearSession($this->newCartSession);
                    $this->sendMail($object);
                } else {
                    $message = 'Заказ не оформлен, так как список товаров пуст';
                }
                return $this->render('SiteFrontendBundle:Order:success.html.twig', [
                    'breadcrumbs' => $menu,
                    'refererUrl' => $previousUrl,
                    'message' => $message
                ]);
            }
        }
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname' => 'order'
        ]);
        return $this->render('SiteFrontendBundle:Order:create.html.twig', [
            'breadcrumbs' => $menu,
            'refererUrl' => $previousUrl,
            'form' => $form->createView(),
            'seo' => $seo
        ]);
    }

    /**
     * method to get number of elements in user cart
     *
     */
    public function getItemsNumberAction(Request $request)
    {

        $itemsNumber = $this->get('fonmaxx.cart.items.number')->getItemsNumber($this->newCartSession);
        $result = json_encode([
            'success' => true,
            'number' => $itemsNumber
        ]);
        return new JsonResponse($result);
    }

    public function addProductAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
        $order = $this->getNewOrderObject();
        $order = $this->addProductToOrder($entity, $order);
        $form = $this->createForm(OrderType::class, $order, [
            'action' => $this->get('router')->generate('site_frontend_order_create', []),
            'container' => $this->container,
            'is_frontend' => true,
            'is_ajax' => true
        ]);
        return $this->render('SiteFrontendBundle:Order:_ajax_cart.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function removeProductAction(Request $request, $slug, $ringSize = null, $insertionColor = null, $chainSize = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Product')->findOneBySlug($slug);
        $this->removeItemFromOrder($entity, $ringSize, $insertionColor, $chainSize);
        return new Response('success');
    }

    public function addSetAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteBackendBundle:Set')->getSetBySlug($slug);
        $order = $this->addSetToOrder($entity);
        $form = $this->createForm(OrderType::class, $order, [
            'action' => $this->get('router')->generate('site_frontend_order_create', []),
            'container' => $this->container,
            'is_frontend' => true,
            'is_ajax' => true
        ]);
        return $this->render('SiteFrontendBundle:Order:_ajax_cart.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function removeSetAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $this->removeSetFromOrder($slug);
        return new Response('success');
    }

    public function getAjaxCartAction(Request $request)
    {
        $order = $this->getNewOrderObject();
        $form = $this->createForm(OrderType::class, $order, [
            'action' => $this->get('router')->generate('site_frontend_order_create', []),
            'container' => $this->container,
            'is_frontend' => true,
            'is_ajax' => true
        ]);
        return $this->render('SiteFrontendBundle:Order:_ajax_cart.html.twig', [
            'form' => $form->createView()
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
            'is_frontend' => true

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
            'is_frontend' => true

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
    private function getRefererUrl(Request $request)
    {
        $previousUrl = null;
        $previousUrl = $request->headers->get('referer');
        $orderCreateUri = $this->get('router')->generate('site_frontend_order_create', [], 0);
        $url = $request->getSession()->get($this->previousUrl);
        if (
            $previousUrl &&
            $request->getUri() != $previousUrl &&
            $previousUrl != $orderCreateUri
        ) {
            $request->getSession()->set($this->previousUrl, $previousUrl);
            $url = $previousUrl;
        }
        return $url;
    }

//    private function removeItemFromSession($entity, $type, $sessionName)
//    {
//        $session = $this->get('session')->get($sessionName);
//        $list = ($session) ? json_decode($session, true) : [];
//        if ((json_last_error() === JSON_ERROR_NONE) && ($entity)) {
//            $keys = array_keys($list);
//            $key = $entity->getId() . '-' . $type;
//            foreach ($list as $k => $item) {
//                $arr = explode('-', $k);
//                $arrKey = (count($arr) > 2) ? $arr[1] . '-' . $arr[2] : null;
//                if ($arrKey == $key) {
//                    unset($list[$k]);
//                    $session = json_encode($list);
//                    $this->get('session')->set($sessionName, $session);
//                    return;
//                }
//            }
//        }
//    }

    private function removeSetFromOrder($slug)
    {
        $order = $this->getNewOrderObject();
        foreach ($order->getOrderHasSets() as $key => $orderHasSets) {
            if ($orderHasSets->getDeleteParameter() == $slug) {
                unset($order->getOrderHasSets()[$key]);
            }
        }
        $this->addOrderToSession($order, $this->newCartSession);
    }

    private function removeItemFromOrder($entity, $ringSize, $insertionColor, $chainSize)
    {
        if ($ringSize == 'null') {
            $ringSize = null;
        }
        if ($insertionColor == 'null') {
            $insertionColor = null;
        }
        if ($chainSize == 'null') {
            $chainSize = null;
        }
        $order = $this->getNewOrderObject();
        foreach ($order->getOrderHasProducts() as $key => $orderHasProduct) {
            if ($orderHasProduct->getProduct()->getId() == $entity->getId()) {
                if (
                    $orderHasProduct->getChainSize() == $chainSize and
                    $orderHasProduct->getRingSize() == $ringSize and
                    $orderHasProduct->getInsertionColor() == $insertionColor
                ) {
                    unset($order->getOrderHasProducts()[$key]);
                }
            }
        }
        $this->addOrderToSession($order, $this->newCartSession);
    }

    private function addOrderToSession($order, $sessionName)
    {
        $this->get('session')->set($sessionName, serialize($order));
    }

//    private function addItemToSession($entity, $type, $sessionName)
//    {
//        $session = $this->get('session')->get($sessionName);
//        $list = ($session) ? json_decode($session, true) : [];
//        if ((json_last_error() === JSON_ERROR_NONE) && ($entity)) {
//            $key = time() . '-' . $entity->getId() . '-' . $type;
//            $list[$key] = $entity->getId();
//            $session = json_encode($list);
//            $this->get('session')->set($sessionName, $session);
//        }
//    }

    private function addProductToOrder($product, Order $order)
    {
        $ringSizes = ($product->getRingSizes()[0]) ? $product->getRingSizes()[0]->getId() : null;
        $chainSize = ($product->getChainSizes()[0]) ? $product->getChainSizes()[0]->getId() : null;
        $insertionColor = ($product->getInsertionColors()[0]) ? $product->getInsertionColors()[0]->getId() : null;

        $inOrder = false;
        foreach ($order->getOrderHasProducts() as $orderHasProduct) {
            if ($orderHasProduct->getProduct()->getId() == $product->getId()) {
                if ((
                        is_null($orderHasProduct->getRingSize()) and
                        is_null($orderHasProduct->getChainSize()) and
                        is_null($orderHasProduct->getInsertionColor())
                    ) or
                    (
                        ($orderHasProduct->getRingSize() == $ringSizes) and
                        ($orderHasProduct->getChainSize() == $chainSize) and
                        ($orderHasProduct->getInsertionColor() == $insertionColor)
                    )
                ) {
                    $number = $orderHasProduct->getNumber();
                    $orderHasProduct->setNumber($number + 1);
                    $inOrder = true;
                }
            }
        }

        if ($inOrder == false) {
            $orderHasProducts = new OrderHasProduct();
            $orderHasProducts->setProduct($product);
            $orderHasProducts->setRingSize($ringSizes);
            $orderHasProducts->setChainSize($chainSize);
            $orderHasProducts->setInsertionColor($insertionColor);
            $order->addOrderHasProduct($orderHasProducts);
        }
        $this->addOrderToSession($order, $this->newCartSession);
        return $order;
    }

//    private function getOrderObject()
//    {
//        $em = $this->getDoctrine()->getManager();
//        $session = $this->get('session')->get($this->cartSession);
//        $list = ($session) ? json_decode($session, true) : [];
//        $order = new Order();
//        if ((json_last_error() === JSON_ERROR_NONE) && count($list)) {
//            $productIds = [];
//            $setIds = [];
//            foreach ($list as $key => $item) {
//                $type = explode('-', $key);
//                if ($type[2] == 'set') {
//                    $setIds[] = $item;
//                } else {
//                    $productIds[] = $item;
//                }
//            }
//            $products = $em->getRepository('SiteBackendBundle:Product')->getByAndIndexIds($productIds);
//            $sets = $em->getRepository('SiteBackendBundle:Set')->getByAndIndexIds($setIds);
//            $order = $this->addProducts($order, $products, $productIds);
//            $order = $this->addSets($order, $sets, $setIds);
//        }
//        return $order;
//    }

    private function getNewOrderObject()
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session')->get($this->newCartSession);
        $order = ($session) ? unserialize($session) : new Order();
        $productIds = [];
        $setIds = [];
        foreach ($order->getOrderHasProducts() as $orderHasProducts) {
            $productIds[] = $orderHasProducts->getProduct()->getId();
        }
        foreach ($order->getOrderHasSets() as $orderHasSets) {
            $setIds[] = $orderHasSets->getSet()->getId();
        }
        $products = $em->getRepository('SiteBackendBundle:Product')->getByAndIndexIdsForGetFromSession($productIds);
        $sets = $em->getRepository('SiteBackendBundle:Set')->getByAndIndexIdsForGetFromSession($setIds);
        $order = $this->addProducts($order, $products);
        $order = $this->addSets($order, $sets);
        return $order;
    }

    private function addProducts($order, $products)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($order->getOrderHasProducts() as $orderHasProduct) {
            if (array_key_exists($orderHasProduct->getProduct()->getId(), $products)) {
                $orderHasProduct->setProduct($products[$orderHasProduct->getProduct()->getId()]);
                $em->persist($orderHasProduct);
            }
        }
        return $order;
    }

    private function addSetToOrder($set)
    {
        $order = $this->getNewOrderObject();
        $em = $this->getDoctrine()->getManager();

        if ($set) {
            $orderHasSet = new OrderHasSet();
            $orderHasSet->setSet($set);
            $components = $set->getProducts();
            $tempComponents = [];
            foreach ($components as $component) {
                $tempComponents[$component->getId()] = $component;
            }
            ksort($tempComponents);
            foreach ($tempComponents as $component) {
                $orderHasSetComponent = new OrderHasSetComponent();
                $orderHasSetComponent->setProduct($component);
                $orderHasSet->addOrderHasSetComponent($orderHasSetComponent);
                $em->persist($orderHasSet);
            }

            $orderHasSet->setDeleteParameter(time());
            $inOrder = self::checkOrderHasSet($order, $orderHasSet);

            if ($inOrder == false) {
                $orderHasSet = new OrderHasSet();
                $orderHasSet->setDeleteParameter(time());
                $orderHasSet->setSet($set);
//            $orderHasSet->setOrder($order);
                $components = $set->getProducts();
                foreach ($components as $component) {
                    $orderHasSetComponent = new OrderHasSetComponent();
                    $orderHasSetComponent->setProduct($component);
                    $orderHasSetComponent->setOrderHasSet($orderHasSet);
                    $em->persist($orderHasSetComponent);
                    $orderHasSet->addOrderHasSetComponent($orderHasSetComponent);
                    $em->persist($orderHasSet);
                }
                $order->addOrderHasSet($orderHasSet);
                $em->persist($order);
            }
            $this->addOrderToSession($order, $this->newCartSession);
        }
        return $order;
    }

    private function addSets($order, $sets)
    {
        foreach ($order->getOrderHasSets() as $orderHasSet) {
            if (array_key_exists($orderHasSet->getSet()->getId(), $sets)) {
                $set = $sets[$orderHasSet->getSet()->getId()];
                $orderHasSet->setSet($set);
                $components = $set->getProducts();
                $componentsArr = [];
                foreach ($components as $product) {
                    $componentsArr[$product->getId()] = $product;
                }
                foreach ($orderHasSet->getOrderHasSetComponents() as $component) {
                    if (array_key_exists($component->getProduct()->getId(), $componentsArr)) {
                        $component->setProduct($componentsArr[$component->getProduct()->getId()]);
                    }
                }
            }
        }

        return $order;
    }

    private function clearSession($sessionName)
    {
        $this->get('session')->remove($sessionName);
    }

    private function countOrderSum($object)
    {
        $sum = 0;
        if (count($products = $object->getOrderHasProducts())) {
            foreach ($products as $item) {
                $sum += ($item->getProduct()->getSharePrice()) ? $item->getProduct()->getSharePrice() * $item->getNumber() : $item->getProduct()->getPrice() * $item->getNumber();
            }
        }
        if (count($sets = $object->getOrderHasSets())) {
            foreach ($sets as $item) {
                $number = $item->getNumber();
                if (count($components = $item->getOrderHasSetComponents())) {
                    $tempSum = 0;
                    foreach ($components as $component) {
                        if ($component->getPresence()) {
                            $tempSum += ($component->getProduct()->getSharePrice()) ? $component->getProduct()->getSharePrice() : $component->getProduct()->getPrice();
                        }
                    }
                }
                $sum += $tempSum * $number;
            }
        }
        return $sum;
    }

    private function sendMail($entity)
    {
        return;
        $parameters = $this->container->getParameter('mailer_parameters');
        $message = \Swift_Message::newInstance()
            ->setSubject('Новый заказ ' . '(id:' . $entity->getId() . ')' . ' с сайта "Uvelife.com"')
            ->setFrom($parameters['send_from'])
            ->setBody(
                $this->renderView('SiteFrontendBundle:EMails:_orderManagerMail.html.twig', ['entity' => $entity]),
                'text/html'
            );
        foreach ($parameters['send_to'] as $to) {
            $message->setTo($to);
            $this->get('mailer')->send($message);
        }
    }

    private function checkOrderHasSet($order, $orderHasSet)
    {
        $inOrder = false;
        if (count($order->getOrderHasSets())) {
            foreach ($order->getOrderHasSets() as $item) {
                if ($item->getSet()->getId() == $orderHasSet->getSet()->getId()) {
                    $arr = [];
                    $arrOrder = [];
                    foreach ($item->getOrderHasSetComponents() as $orderHasSetComponent) {
                        $ringSizes = $orderHasSetComponent->getRingSize();
                        $chainSize = $orderHasSetComponent->getChainSize();
                        $insertionColor = $orderHasSetComponent->getInsertionColor();
                        $presence = $orderHasSetComponent->getPresence();
                        $arr[$orderHasSetComponent->getProduct()->getId()] = [
                            'insertionColor' => $insertionColor,
                            'chainSize' => $chainSize,
                            'ringSizes' => $ringSizes,
                            'presence' => $presence,
                        ];
                    }
                    foreach ($orderHasSet->getOrderHasSetComponents() as $orderHasSetComponent) {
                        $ringSizes = $orderHasSetComponent->getRingSize();
                        $chainSize = $orderHasSetComponent->getChainSize();
                        $insertionColor = $orderHasSetComponent->getInsertionColor();
                        $presence = $orderHasSetComponent->getPresence();
                        $arrOrder[$orderHasSetComponent->getProduct()->getId()] = [
                            'insertionColor' => $insertionColor,
                            'chainSize' => $chainSize,
                            'ringSizes' => $ringSizes,
                            'presence' => $presence,
                        ];
                    }
                    $isInOrderArr = [];
                    foreach ($arr as $k => $item2) {
                        if (
                            $item2['insertionColor'] == $arrOrder[$k]['insertionColor'] and
                            $item2['chainSize'] == $arrOrder[$k]['chainSize'] and
                            $item2['ringSizes'] == $arrOrder[$k]['ringSizes'] and
                            $item2['presence'] == $arrOrder[$k]['presence']
                        ) {
                            $isInOrderArr[] = true;
                        } else {
                            $isInOrderArr[] = false;
                        }
                    }
                    $isInOrder = false;
                    foreach ($isInOrderArr as $item3) {
                        $isInOrder = true;
                        if ($item3 == false) {
                            $isInOrder = false;
                            break;
                        }
                    }
                    if ($isInOrder == true) {
                        $inOrder = true;
                        $number = $item->getNumber();
                        $item->setNumber($number + 1);
                    }
                }
            }
        }

        $this->addOrderToSession($order, $this->newCartSession);

        return $inOrder;
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
    private function checkOrderHasSetSubmit($request, $order)
    {
        $em = $this->getDoctrine()->getManager();
        $orderHasSet = $this->getOrderHasSetObject($request);
        if (!$orderHasSet) {
            return $order;
        }
        $orderHasSetForm = $this->createForm(OrderHasSetType::class, $orderHasSet, []);
        if ($request->isMethod('POST') && $request->request->has($orderHasSetForm->getName())) {
            $orderHasSetForm->handleRequest($request);
//            if ($orderHasSetForm->isValid()) {
//            we just need to add orderHasSet to current Order to have all states selected in show
//            and add it to session
            $orderHasSet->setDeleteParameter(time());
            $inOrder = self::checkOrderHasSet($order, $orderHasSet);
            if ($inOrder == false) {
                $order->addOrderHasSet($orderHasSet);
            }
            $em->persist($order);
            $this->addOrderToSession($order, $this->newCartSession);
//                $this->addItemToSession($orderHasSet->getSet(),'set',$this->cartSession);
//            } else {
//                return $order;
//            }
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
    private function checkOrderHasProductSubmit($request, $order)
    {
        $em = $this->getDoctrine()->getManager();
        $orderHasProduct = $this->getOrderHasProductObject($request);
        if (!$orderHasProduct) {
            return $order;
        }
        $isAdd = true;
        $orderHasProductForm = $this->createForm(OrderHasProductType::class, $orderHasProduct, []);
        if ($request->isMethod('POST') && $request->request->has($orderHasProductForm->getName())) {
            $orderHasProductForm->handleRequest($request);
            if ($orderHasProductForm->isValid()) {
                if (count($order->getOrderHasProducts())) {
                    foreach ($order->getOrderHasProducts() as $item) {
                        if ($item->getProduct()->getId() == $orderHasProduct->getProduct()->getId()) {
                            if (
                                $item->getRingSize() == $orderHasProduct->getRingSize() and
                                $item->getChainSize() == $orderHasProduct->getChainSize() and
                                $item->getInsertionColor() == $orderHasProduct->getInsertionColor()
                            ) {
                                $number = $item->getNumber();
                                $item->setNumber($number + 1);
                                $isAdd = false;
                            }
                            $em->persist($order);
                        }
                    }
                }
                if ($isAdd) {
                    $order->addOrderHasProduct($orderHasProduct);
                }
                $em->persist($order);
                $this->addOrderToSession($order, $this->newCartSession);
//                $this->addItemToSession($orderHasProduct->getProduct(),'product',$this->cartSession);
            } else {
                return false;
            }
        }
        return $order;
    }

    private function getOrderHasProductObject($request)
    {
        $em = $this->getDoctrine()->getManager();
        $slug = $request->query->get('slug', null);
        if ($slug) {
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

    private function getOrderHasSetObject($request)
    {
        $em = $this->getDoctrine()->getManager();
        $slug = $request->query->get('slug', null);
        if ($slug) {
            $set = $em->getRepository('SiteBackendBundle:Set')->getSetBySlug($slug);
            if ($set) {
                $orderHasSet = new OrderHasSet();
                $orderHasSet->setSet($set);
                $components = $set->getProducts();
                $tempComponents = [];
                foreach ($components as $component) {
                    $tempComponents[$component->getId()] = $component;
                }
                ksort($tempComponents);
                foreach ($tempComponents as $component) {
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
