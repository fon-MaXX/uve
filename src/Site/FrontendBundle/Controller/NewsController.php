<?php

namespace Site\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\FrontendBundle\Form\NewsFilterType;

class NewsController extends Controller
{
    private $newsNumber = 10;
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'главная'
            ],
            'last'=>"Новости"
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $newsNumber = $this->newsNumber;
        $newsQuery = $em->getRepository('SiteBackendBundle:News')->getForNewsList();
        $paginator = $this->get('knp_paginator');
        $news = $paginator->paginate(
            $newsQuery,
            $request->query->get('page',1),
            $newsNumber
        );
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>'news'
        ]);
        return $this->render('SiteFrontendBundle:News:list.html.twig', [
            'breadcrumbs'=>$menu,
            'news'=>$news,
            'seo'=>$seo
        ]);
    }
    public function scrollNewsListPageAction(Request $request,$page){
        if(!$page){
            return new Response(" ");
        }
        $em = $this->getDoctrine()->getManager();
        $newsNumber = $this->newsNumber;
        $newsQuery = $em->getRepository('SiteBackendBundle:News')->getForNewsList();
        $paginator = $this->get('knp_paginator');
        $news = $paginator->paginate(
            $newsQuery,
            $page,
            $newsNumber
        );
        return $this->render('SiteFrontendBundle:News:_jscroll_list.html.twig', [
            'news'=>$news,
        ]);
    }
    public function tagListAction(Request $request,$slug)
    {
        $em = $this->getDoctrine()->getManager();
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'главная'
            ],
            'last'=>"Новости"
        ];
        $tag = $em->getRepository('SiteBackendBundle:NewsTag')->findOneBySlug($slug);
        if(!$tag){
            throw new NotFoundHttpException("Новостей с параметром тег = ".$slug.' не найдено');
        }
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $newsNumber = $this->newsNumber;
        $newsQuery = $em->getRepository('SiteBackendBundle:News')->getForNewsList($slug);
        $paginator = $this->get('knp_paginator');
        $news = $paginator->paginate(
            $newsQuery,
            $request->query->get('page',1),
            $newsNumber
        );
        return $this->render('SiteFrontendBundle:News:tagList.html.twig', [
            'breadcrumbs'=>$menu,
            'news'=>$news,
            'tag'=>$tag
        ]);
    }
    public function scrollTagNewsListPageAction(Request $request,$slug,$page){
        if(!$page){
            return new Response(" ");
        }
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('SiteBackendBundle:NewsTag')->findOneBySlug($slug);
        if(!$tag){
            throw new NotFoundHttpException("Новостей с параметром тег = ".$slug.' не найдено');
        }
        $newsNumber = $this->newsNumber;
        $newsQuery = $em->getRepository('SiteBackendBundle:News')->getForNewsList($slug);
        $paginator = $this->get('knp_paginator');
        $news = $paginator->paginate(
            $newsQuery,
            $page,
            $newsNumber
        );
        return $this->render('SiteFrontendBundle:News:_jscroll_tag_list.html.twig', [
            'news'=>$news,
            'tag'=>$tag
        ]);
    }
    public function showAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('SiteBackendBundle:News')->findOneBy([
            'slug'=>$slug
        ]);
        if(!$article){
            throw new NotFoundHttpException('Новость с параметром = '.$slug.' не найдена');
        }
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'главная'
            ],
            'news'=>[
                'parameters'=>[],
                'title'=>'новости'
            ],
            'last'=>$article->getTitle()
        ];
        $tag = $article->getNewsTags()[0];
        $recomended = $em->getRepository('SiteBackendBundle:News')->getRecomended(3,$tag->getSlug());
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        return $this->render('SiteFrontendBundle:News:show.html.twig', [
            'breadcrumbs'=>$menu,
            'article'=>$article,
            'recomended'=>$recomended,
            'tag'=>$tag
        ]);
    }
}
