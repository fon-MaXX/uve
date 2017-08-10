<?php

namespace Site\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Site\BackendBundle\Entity\FilterConfig;
use Site\FrontendBundle\Form\OrderHasSetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\FrontendBundle\Form\SetFilterType;
use Site\BackendBundle\Entity\Category;
use Site\BackendBundle\Entity\OrderHasSet;
use Site\BackendBundle\Entity\OrderHasSetComponent;
use Site\FrontendBundle\Form\SearchType;
use Site\BackendBundle\Entity\Comment;
use Site\FrontendBundle\Form\CommentType;

class SetController extends Controller
{
    private $listSession = 'set_controller_list';
    private $cartSession = 'cart_session';
    private $newCartSession = 'order_cart_session';
    private $numberOnPage = [
        20=>'20 на страницу',
        40=>'40 на страницу',
        60=>'60 на страницу',

    ];
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'главная'
            ],
            'last'=>"Наборы"
        ];
        $categories = $em->getRepository('SiteBackendBundle:Category')->getCategoriesWithSubCategoriesIndexBySlug();
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);

        $configObject = $em->getRepository(FilterConfig::class)->findFilterConfig();
        $config = $configObject->getFilterConfig('наборы');
        $form = $this->createForm(SetFilterType::class,[],[
            'action'=>$this->get('router')->generate('site_frontend_set_list',[]),
            'filter_config'=>$config
        ]);
        $setNumber = key($this->numberOnPage);
        if(!$request->query->get('page')){
            $this->get('session')->remove($this->listSession);
        }
        $filter = $this->getFilterFromSession($this->listSession);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $filter=$form->getData();
                $session  = json_encode($filter);
                $this->get('session')->set($this->listSession,$session);
            }
        }
        elseif($filter){
            $form->submit($filter);
        }
        $setNumber = (isset($filter['number']))?(int)$filter['number']:$setNumber;
        $setQuery = $em->getRepository('SiteBackendBundle:Set')->getForSetList($filter,$config);
        $paginator = $this->get('knp_paginator');
        $sets = $paginator->paginate(
            $setQuery,
            $request->query->get('page',1),
            $setNumber
        );
        $numberOnPageSelect = $this->getNumberOnPageSelect($setNumber,'product-list-number-select');
        $sets->setTemplate('SiteFrontendBundle:Product:_list_pagination.html.twig');
        $shareTags=$em->getRepository('SiteBackendBundle:ShareTag')->getShareTagsIndexByTitle();
        $itemsNumber = $this->get('fonmaxx.cart.items.number')->getItemsNumber($this->newCartSession);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('footer_and_header');
        $searchForm = $this->createForm(SearchType::class,[],[
            'action'=>$this->get('router')->generate('site_frontend_search')
        ]);
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>'set_list'
        ]);
        return $this->render('SiteFrontendBundle:Set:list.html.twig', [
            'category'=>new Category(),
            'categories'=>$categories,
            'breadcrumbs'=>$menu,
            'form'=>$form->createView(),
            'sets'=>$sets,
            'numberOnPageSelect'=>$numberOnPageSelect,
            'shareTags'=>$shareTags,
            'itemsNumber'=>$itemsNumber,
            'staticContent'=>$staticContent,
            'searchForm'=>$searchForm->createView(),
            'seo'=>$seo
        ]);
    }
    public function showAction(Request $request,$slug)
    {
        $em = $this->getDoctrine()->getManager();
        $set = $em->getRepository('SiteBackendBundle:Set')->getSetBySlug($slug);
        if(!$set){
            throw new NotFoundHttpException('Товар с параметром = '.$slug.' не найден');
        }
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'Главная'
            ],
            'set'=>[
                'parameters'=>[],
                'title'=>"Наборы"
            ],
            'last'=>$set->getTitle()
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $orderHasSet = $this->createOrderHasSet($set);
        $form = $this->createForm(OrderHasSetType::class,$orderHasSet,[
            'action'=>$this->get('router')->generate('site_frontend_order_create',['slug'=>$set->getSlug()])
        ]);
        $features = $this->getFeaturesArray($set);
        $rand = $em->getRepository('SiteBackendBundle:Set')->getRandSets(5);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('product_show');

        $comment = new Comment();
        $comment->setPageUrl($request->getPathInfo());
        $comments = $em->getRepository('SiteBackendBundle:Comment')->getCommentsByStateAndPath('approved',$request->getPathInfo());
        $commentForm = $this->createForm(CommentType::class,$comment,[
            'action'=>$this->get('router')->generate('site_frontend_add_comment',[
                'type'=>'comment'
            ])
        ]);

        return $this->render('SiteFrontendBundle:Set:show.html.twig', [
            'breadcrumbs'=>$menu,
            'set'=>$set,
            'form'=>$form->createView(),
            'features'=>$features,
            'rand'=>$rand,
            'staticContent'=>$staticContent,
            'comments'=>$comments,
            'commentForm'=>$commentForm->createView()
        ]);
    }
    private function getFilterFromSession($sessionName=null){
        $session = $this->get('session')->get($sessionName);
        $result = json_decode($session,true);
        if ((json_last_error() === JSON_ERROR_NONE)&&($result)) {
            return $result;
        }
        return [];
    }
    private function getNumberOnPageSelect($productNumber,$class){
        $res = "<select class='".$class."'>";
        foreach($this->numberOnPage as $k=>$val){
            if($k==$productNumber){
                $res.="<option value='".$k."' selected >".$val."</option>";
            }
            else{
                $res.="<option value='".$k."'>".$val."</option>";
            }
        }
        $res.="</select>";
        return $res;
    }
    private function getFeaturesArray($product){
        $features = [
            'metal'=>'Металл:',
            'insertionType'=>'Тип вставки:',
            'theme'=>'Тематика:',
        ];
        $result=[];
        foreach($features as $k=>$item){
            $getter = 'get'.ucfirst($k);
            if($product->$getter()){
                $result[$k]['label']=$item;
                $result[$k]['value']=$product->$getter();
            }
        }
        return $result;
    }
    private function createOrderHasSet($set){
        $em = $this->getDoctrine()->getManager();
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
