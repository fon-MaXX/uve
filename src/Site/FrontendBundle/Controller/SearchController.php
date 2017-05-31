<?php

namespace Site\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\FrontendBundle\Form\SearchType;
use Site\BackendBundle\Entity\Category;

class SearchController extends Controller
{
    private $cartSession = 'cart_session';
    public function searchAction(Request $request){
        $form = $this->createForm(SearchType::class,[],[
            'action'=>$this->get('router')->generate('site_frontend_search')
        ]);
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'Главная'
            ],
            'last'=>'Результаты поиска'
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $em = $this->getDoctrine()->getManager();
        $result=[];
        if($request->isMethod("POST")){
            $form->handleRequest($request);
            if($form->isValid()){
                $title = $form->getData()['text'];
                $news = $em->getRepository('SiteBackendBundle:News')->search($title);
                $products = $em->getRepository('SiteBackendBundle:Product')->search($title);
                $sets = $em->getRepository('SiteBackendBundle:Set')->search($title);
                $result = array_merge($result,$products);
                $result = array_merge($result,$sets);
                $result = array_merge($result,$news);
            }
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            20
        );
        $pagination->setTemplate('SiteFrontendBundle:Product:_list_pagination.html.twig');
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('footer_and_header');
        $categories = $em->getRepository('SiteBackendBundle:Category')->getCategoriesWithSubCategoriesIndexBySlug();
        $itemsNumber = $this->get('fonmaxx.cart.items.number')->getItemsNumber($this->cartSession);
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>'search'
        ]);
        return $this->render('SiteFrontendBundle:Search:searchResult.html.twig', [
                'pagination' => $pagination,
                'breadcrumbs'=>$menu,
                'staticContent'=>$staticContent,
                'categories'=>$categories,
                'category'=> new Category(),
                'itemsNumber'=>$itemsNumber,
                'searchForm'=>$form->createView(),
                'seo'=>$seo
        ]);
    }
}
