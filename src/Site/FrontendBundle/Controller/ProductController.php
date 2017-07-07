<?php

namespace Site\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Site\BackendBundle\Entity\FilterConfig;
use Site\BackendBundle\Entity\OrderHasProduct;
use Site\FrontendBundle\Form\OrderHasProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\FrontendBundle\Form\ProductFilterType;
use Site\FrontendBundle\Form\ProductShowType;
use Site\FrontendBundle\Form\SearchType;

class ProductController extends Controller
{
    private $listSession = 'product_controller_list';
    private $cartSession = 'cart_session';
    private $newCartSession = 'order_cart_session';
    private $numberOnPage = [
        20=>'20 на страницу',
        40=>'40 на страницу',
        60=>'60 на страницу',

    ];
    public function listAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('SiteBackendBundle:Category')->findOneBy([
            'slug'=>$slug
        ]);
        if(!$category){
            throw new NotFoundHttpException('Категория с параметром = '.$slug.' не найдена');
        }
        $categories = $em->getRepository('SiteBackendBundle:Category')->getCategoriesWithSubCategoriesIndexBySlug();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'главная'
            ],
            'last'=>$category->getTitle()
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $subCatList=[];
        $subCatFlag=true;
        if(count($category->getSubCategories())>1){
            $subCatFlag=false;
            foreach($category->getSubCategories() as $item){
                $subCatList[$item->getId()]= $item->getTitle();
            }
        }
        $configObject = $em->getRepository(FilterConfig::class)->findFilterConfig();
        $config = $configObject->getFilterConfig($category->getTitle());
        $form = $this->createForm(ProductFilterType::class,[],[
            'action'=>$this->get('router')->generate('site_frontend_category',['slug'=>$slug]),
            'is_sub_category'=>$subCatFlag,
            'sub_category_list'=>$subCatList,
            'filter_config'=>$config
        ]);
        $productNumber = key($this->numberOnPage);
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
        $productNumber = (isset($filter['number']))?(int)$filter['number']:$productNumber;
        $productQuery = $em->getRepository('SiteBackendBundle:Product')->getForProductList($filter,$config,$category->getId());
        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $productQuery,
            $request->query->get('page',1),
            $productNumber
        );
        $numberOnPageSelect = $this->getNumberOnPageSelect($productNumber,'product-list-number-select');
        $products->setTemplate('SiteFrontendBundle:Product:_list_pagination.html.twig');
        $shareTags=$em->getRepository('SiteBackendBundle:ShareTag')->getShareTagsIndexByTitle();
        $itemsNumber = $this->get('fonmaxx.cart.items.number')->getItemsNumber($this->newCartSession);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('footer_and_header');
        $searchForm = $this->createForm(SearchType::class,[],[
            'action'=>$this->get('router')->generate('site_frontend_search')
        ]);
        return $this->render('SiteFrontendBundle:Product:list.html.twig', [
            'category'=>$category,
            'categories'=>$categories,
            'breadcrumbs'=>$menu,
            'form'=>$form->createView(),
            'products'=>$products,
            'numberOnPageSelect'=>$numberOnPageSelect,
            'shareTags'=>$shareTags,
            'itemsNumber'=>$itemsNumber,
            'staticContent'=>$staticContent,
            'searchForm'=>$searchForm->createView()
        ]);
    }
    public function subCatListAction(Request $request,$catSlug,$subSlug){
        $em = $this->getDoctrine()->getManager();
        $subCategory = $em->getRepository('SiteBackendBundle:SubCategory')->getByCatAndSlug($catSlug,$subSlug);
        if(!$subCategory){
            throw new NotFoundHttpException('Подкатегория с параметрами = '.$catSlug.'/'.$subSlug.' не найдена');
        }
        $categories = $em->getRepository('SiteBackendBundle:Category')->getCategoriesWithSubCategoriesIndexBySlug();
        $parentCategory = $subCategory->getCategory();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'Главная'
            ],
            'category'=>[
                'parameters'=>[
                    'slug'=>$parentCategory->getSlug()
                ],
                'title'=>$parentCategory->getTitle()
            ],
            'last'=>$subCategory->getTitle()
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $configObject = $em->getRepository(FilterConfig::class)->findFilterConfig();
        $config = $configObject->getFilterConfig($parentCategory->getTitle());
        $form = $this->createForm(ProductFilterType::class,[],[
            'action'=>$this->get('router')->generate('site_frontend_sub_category',[
                'catSlug'=>$parentCategory->getSlug(),
                'subSlug'=>$subCategory->getSlug()
            ]),
            'is_sub_category'=>true,
            'filter_config'=>$config
        ]);
        $productNumber = key($this->numberOnPage);
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
        $productNumber = (isset($filter['number']))?(int)$filter['number']:$productNumber;
        $productQuery = $em->getRepository('SiteBackendBundle:Product')->getForProductList($filter,$config,$parentCategory->getId(),$subCategory->getId());
        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $productQuery,
            $request->query->get('page',1),
            $productNumber
        );
        $numberOnPageSelect = $this->getNumberOnPageSelect($productNumber,'product-list-number-select');
        $products->setTemplate('SiteFrontendBundle:Product:_list_pagination.html.twig');
        $shareTags=$em->getRepository('SiteBackendBundle:ShareTag')->getShareTagsIndexByTitle();
        $itemsNumber = $this->get('fonmaxx.cart.items.number')->getItemsNumber($this->newCartSession);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('footer_and_header');
        $searchForm = $this->createForm(SearchType::class,[],[
            'action'=>$this->get('router')->generate('site_frontend_search')
        ]);
        return $this->render('SiteFrontendBundle:Product:list.html.twig', [
            'category'=>$parentCategory,
            'subCategory'=>$subCategory,
            'categories'=>$categories,
            'breadcrumbs'=>$menu,
            'form'=>$form->createView(),
            'products'=>$products,
            'numberOnPageSelect'=>$numberOnPageSelect,
            'shareTags'=>$shareTags,
            'itemsNumber'=>$itemsNumber,
            'staticContent'=>$staticContent,
            'searchForm'=>$searchForm->createView()
        ]);
    }
    public function showAction(Request $request,$slug)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('SiteBackendBundle:Product')->getOneWithCategoryAndSubCategory($slug);
        if(!$product){
            throw new NotFoundHttpException('Товар с параметром = '.$slug.' не найден');
        }
        $subCategory = $product->getSubCategory();
        $category = $subCategory->getCategory();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'Главная'
            ],
            'category'=>[
                'parameters'=>[
                    'slug'=>$category->getSlug()
                ],
                'title'=>$category->getTitle()
            ],
            'last'=>$product->getTitle()
        ];
        if(count($category->getSubCategories())>1){
            $arr['sub_category']=[
                'parameters'=>[
                    'catSlug'=>$category->getSlug(),
                    'subSlug'=>$subCategory->getSlug(),
                ],
                'title'=>$subCategory->getTitle()
            ];
        }
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $orderHasProduct = new OrderHasProduct();
        $orderHasProduct->setProduct($product);
        $em->persist($orderHasProduct);
        $form = $this->createForm(OrderHasProductType::class,$orderHasProduct,[
            'action'=>$this->get('router')->generate('site_frontend_order_create',['slug'=>$product->getSlug()])
        ]);
        $rand = $em->getRepository('SiteBackendBundle:Product')->getRandProducts(5,$category->getId());
        $features = $this->getFeaturesArray($product);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('product_show');
        return $this->render('SiteFrontendBundle:Product:show.html.twig', [
            'breadcrumbs'=>$menu,
            'product'=>$product,
            'form'=>$form->createView(),
            'features'=>$features,
            'rand'=>$rand,
            'staticContent'=>$staticContent
        ]);
    }
    private function getFilterFromSession($sessionName=null){
        $session = $this->get('session')->get($sessionName);
        $result = ($session)?json_decode($session,true):[];
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
            'weight'=>'Средний вес,г:',
            'metal'=>'Металл:',
            'insertionType'=>'Тип вставки:',
            'insertionShape'=>'Форма вставки:',
            'insertionParameters'=>'Параметры вставки:',
            'productParameters'=>'Параметры изделия:',
            'weavingType'=>'Тип плетения:',
            'covering'=>'Покрытие:',
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
}
