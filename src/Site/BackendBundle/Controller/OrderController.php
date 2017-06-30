<?php

namespace Site\BackendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Site\BackendBundle\Entity\OrderAddProduct;
use Site\BackendBundle\Entity\OrderAddSet;
use Site\BackendBundle\Entity\OrderHasProduct;
use Site\BackendBundle\Entity\Order;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Site\FrontendBundle\Form\OrderType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\BackendBundle\Form\AddProductType;
use Site\BackendBundle\Form\AddSetType;
use Site\BackendBundle\Entity\OrderHasSet;
use Site\BackendBundle\Entity\OrderHasSetComponent;
use Symfony\Component\Form\FormView;

class OrderController extends Controller
{
    public function newpostformAction(Request $request)
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
            'is_frontend'=>false

        ]);
        $form->submit($data);
        return $this->render('SiteFrontendBundle:Order:_novaPoshtaDataForm.html.twig', array(
            'form' => $form->createView()
        ));
    }
    public function ukrposhtaformAction(Request $request)
    {
        $data = $request->request->all();
//        $data=[
//            "ukrPoshtaData"=>[
//                "address"=>"test",
//
//            ]
//        ];
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order, [
            'container' => $this->container,
            'is_frontend'=>false

        ]);
        $form->submit($data);
        return $this->render('SiteFrontendBundle:Order:_ukrPoshtaDataForm.html.twig', array(
            'form' => $form->createView()
        ));
    }
    public function createAction()
    {
        $request = $this->getRequest();
        // the key used to lookup the template
        $templateKey = 'edit';

        $this->admin->checkAccess('create');

        $class = new \ReflectionClass($this->admin->hasActiveSubClass() ? $this->admin->getActiveSubClass() : $this->admin->getClass());

        if ($class->isAbstract()) {
            return $this->render(
                'SonataAdminBundle:CRUD:select_subclass.html.twig',
                array(
                    'base_template' => $this->getBaseTemplate(),
                    'admin' => $this->admin,
                    'action' => 'create',
                ),
                null,
                $request
            );
        }

        $object = $this->admin->getNewInstance();

        $preResponse = $this->preCreate($request, $object);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //TODO: remove this check for 4.0
            if (method_exists($this->admin, 'preValidate')) {
                $this->admin->preValidate($object);
            }
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $this->admin->checkAccess('create', $object);

                try {
                    $object = $this->admin->create($object);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                            'result' => 'ok',
                            'objectId' => $this->admin->getNormalizedIdentifier($object),
                        ), 200, array());
                    }

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->trans(
                            'flash_create_success',
                            array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                            'SonataAdminBundle'
                        )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($object);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                        'sonata_flash_error',
                        $this->trans(
                            'flash_create_error',
                            array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                            'SonataAdminBundle'
                        )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->render('SiteBackendBundle:Order:create.html.twig', array(
            'action' => 'create',
            'form' => $formView,
            'object' => $object,
        ), null);
    }
    public function editAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('edit', $object);

        $preResponse = $this->preEdit($request, $object);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($object);

        /** @var $form Form */
        $form = $this->createForm(OrderType::class,$object,[
            'action'=>$this->get('router')->generate('admin_site_backend_order_edit',['id'=>$object->getId()]),
            'container'=>$this->container
        ]);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $order = $form->getData();
                $order = $this->performRemove($order);
                $order->setPrice($this->countOrderSum($order));
                $em->persist($order);
                $em->flush();
                $this->addFlash(
                    'sonata_flash_success',
                    $this->trans(
                        'flash_edit_success',
                        array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                        'SonataAdminBundle'
                    )
                );
                $object = $this->admin->update($order);
                return $this->redirectTo($object);
            }
        }
        return $this->render("SiteBackendBundle:Order:_edit.html.twig", array(
            'action' => 'edit',
            'form' => $form->createView(),
            'object' => $object,
        ), null);
    }
    public function orderaddproductAction(Request $request, $id){
        $em=$this->getDoctrine()->getManager();
        $order = $em->getRepository('SiteBackendBundle:Order')->findOneById($id);
        if(!$order){
            throw new NotFoundHttpException('No order with id='.$id.' found');
        }
        $categories = $em->getRepository('SiteBackendBundle:SubCategory')->getSubCategoriesIndexById();
        $catId = $request->query->get('catid',null);
        $category = ($catId)?$em->getRepository('SiteBackendBundle:SubCategory')->findOneById($catId):null;
        $data = ($category)?$category->getId():key($categories);
        $products = $em->getRepository('SiteBackendBundle:Product')->getAllBySubCategoryId($data);
        $orderAddProduct = new OrderAddProduct();
        $orderAddProduct->setCategory($data);
        $orderAddProduct->setProducts($products);
        $form = $this->createForm(AddProductType::class,$orderAddProduct,[
            'action'=>$this->get('router')->generate('admin_site_backend_order_orderaddproduct',[
                'id'=>$id,
                'catid'=>$data
            ]),
            'choices'=>$categories
        ]);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $entity = $form->getData();
                foreach($entity->getProducts() as $product){
                    if($product->getAddProduct()){
                        $order = $this->addProductToOrder($product,$order);
                    }
                }
                $order->setPrice($this->countOrderSum($order));
                $em->persist($order);
                $em->flush();
                return $this->redirectToRoute('admin_site_backend_order_edit',['id'=>$id]);
            }
        }
        return $this->render('@SiteBackend/Order/_add_product.html.twig',[
            'action' => 'orderaddproduct',
            'form' => $form->createView(),
            'object' => $order,
        ]);
    }
    public function orderaddsetAction(Request $request, $id){
        $em=$this->getDoctrine()->getManager();
        $order = $em->getRepository('SiteBackendBundle:Order')->findOneById($id);
        if(!$order){
            throw new NotFoundHttpException('No order with id='.$id.' found');
        }
        $orderAddSet = new OrderAddSet();
        $sets = $em->getRepository('SiteBackendBundle:Set')->findAll();
        $orderAddSet->setSets($sets);
        $form = $this->createForm(AddSetType::class,$orderAddSet,[
            'action'=>$this->get('router')->generate('admin_site_backend_order_orderaddset',[
                'id'=>$id
            ]),
        ]);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $entity=$form->getData();
                foreach($entity->getSets() as $set){
                    if($set->getAddSet()){
                        $order = $this->addSetToOrder($set,$order);
                    }
                }
                $order->setPrice($this->countOrderSum($order));
                $em->persist($order);
                $em->flush();
                return $this->redirectToRoute('admin_site_backend_order_edit',['id'=>$id]);
            }
        }
        return $this->render('@SiteBackend/Order/_add_set.html.twig',[
            'action' => 'orderaddset',
            'form' => $form->createView(),
            'object' => $order,
        ]);
    }
    private function addProductToOrder($product,$order){
        $em=$this->getDoctrine()->getManager();
        $orderHasProduct = new OrderHasProduct();
        $orderHasProduct->setProduct($product);
        $orderHasProduct->setNumber(1);
        $em->persist($orderHasProduct);
        $order->addOrderHasProduct($orderHasProduct);
        $em->persist($order);
        return $order;
    }
    private function addSetToOrder($set,$order){
        $em=$this->getDoctrine()->getManager();
        $orderHasSet = new OrderHasSet();
        $orderHasSet->setSet($set);
        $orderHasSet->setNumber(1);
        if(count($products=$set->getProducts())){
            foreach($products as $product){
                $orderHasSetComponent = new OrderHasSetComponent();
                $orderHasSetComponent->setProduct($product);
                $em->persist($orderHasSetComponent);
                $orderHasSet->addOrderHasSetComponent($orderHasSetComponent);
            }
        }
        $em->persist($orderHasSet);
        $order->addOrderHasSet($orderHasSet);
        $em->persist($order);
        return $order;
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
    private function performRemove($order){
        $em = $this->getDoctrine()->getManager();
        if(count($orderHasProducts = $order->getOrderHasProducts())){
            foreach($orderHasProducts as $item){
                if($item->getDelete()){
                    $order->removeOrderHasProduct($item);
                    $em->persist($order);
                    $em->remove($item);
                }
            }
        }
        if(count($orderHasSets = $order->getOrderHasSets())){
            foreach($orderHasSets as $item){
                if($item->getDelete()){
                    $order->removeOrderHasSet($item);
                    $em->persist($order);
                    $em->remove($item);
                }
            }
        }
        return $order;
    }
    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     *
     * @param FormView $formView
     * @param string   $theme
     */
    private function setFormTheme(FormView $formView, $theme)
    {
        $twig = $this->get('twig');

        try {
            $twig
                ->getRuntime('Symfony\Bridge\Twig\Form\TwigRenderer')
                ->setTheme($formView, $theme);
        } catch (\Twig_Error_Runtime $e) {
            // BC for Symfony < 3.2 where this runtime not exists
            $twig
                ->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')
                ->renderer
                ->setTheme($formView, $theme);
        }
    }
}
