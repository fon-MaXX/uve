<?php

namespace Site\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Site\BackendBundle\Entity\Contacts;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Site\BackendBundle\Entity\Category;
use Site\FrontendBundle\Form\CallbackType;
use Site\FrontendBundle\Form\ContactsType;
use Site\FrontendBundle\Form\SearchType;
use Site\BackendBundle\Entity\Callback;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    private $cartSession = 'cart_session';
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $novels = $em->getRepository('SiteBackendBundle:Product')->getLastByTagAndNumber(5,'новинка');
        $hits = $em->getRepository('SiteBackendBundle:Product')->getLastByTagAndNumber(5,'топ продаж');
        $news = $em->getRepository('SiteBackendBundle:News')->getLastByNumber(4);
        $slides = $em->getRepository('SiteBackendBundle:Slider')->getLastByNumber(10);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('main_page');
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>'main'
        ]);
        return $this->render('SiteFrontendBundle:Default:index.html.twig', [
            'novels'=>$novels,
            'hits'=>$hits,
            'news'=>$news,
            'slides'=>$slides,
            'staticContent'=>$staticContent,
            'seo'=>$seo
        ]);
    }
    public function headerMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SiteBackendBundle:Category')->getCategoriesWithSubCategoriesIndexBySlug();
        $itemsNumber = $this->get('fonmaxx.cart.items.number')->getItemsNumber($this->cartSession);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('footer_and_header');
        $searchForm = $this->createForm(SearchType::class,[],[
            'action'=>$this->get('router')->generate('site_frontend_search')
        ]);
        return $this->render('SiteFrontendBundle:Parts:_header.html.twig', [
            'category'=>new Category(),
            'categories'=>$categories,
            'itemsNumber'=>$itemsNumber,
            'staticContent'=>$staticContent,
            'searchForm'=>$searchForm->createView()
        ]);
    }
    public function footerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SiteBackendBundle:Category')->getCategoriesWithSubCategoriesIndexBySlug();
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('footer_and_header');
        return $this->render('SiteFrontendBundle:Parts:_footer.html.twig', [
            'categories'=>$categories,
            'staticContent'=>$staticContent
        ]);
    }
    public function getCallbackFormAction(Request $request){
        $callback = new Callback();
        $form = $this-> createForm(CallbackType::class,$callback,[
            'action'=>$this->get('router')->generate('site_frontend_receive_callback')
        ]);
        return $this->render('SiteFrontendBundle:Form:_callback_form.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    public function receiveCallbackAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $callback = new Callback();
        $form = $this-> createForm(CallbackType::class,$callback,[
            'action'=>$this->get('router')->generate('site_frontend_receive_callback')
        ]);
        $form->handleRequest($request);
        $response=[
            'success'=>false,
            'message'=>''
        ];
        if($form->isValid()){
            $callback = $form->getData();
            $em->persist($callback);
            $em->flush();
            $response=[
                'success'=>true,
                'message'=>'Мы свяжемся с вами в ближайшее время'
            ];
        }
        return new JsonResponse(json_encode($response));
    }
    public function staticAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $templateName = $request->get('_route');
        $templates=[
            'site_frontend_about_company'=>[
                'template'=>'SiteFrontendBundle:Default:about_company.html.twig',
                'page'=>'about_company',
                'title'=>'О нас'
                ],
            'site_frontend_contacts'=>[
                'template'=>'SiteFrontendBundle:Default:contacts.html.twig',
                'page'=>'contacts',
                'title'=>'Контакты'
            ],
            'site_frontend_about_delivery'=>[
                'template'=>'SiteFrontendBundle:Default:about_delivery.html.twig',
                'page'=>'about_delivery',
                'title'=>'Доставка'
            ],
            'site_frontend_about_payment'=>[
                'template'=>'SiteFrontendBundle:Default:about_payment.html.twig',
                'page'=>'about_payment',
                'title'=>'Оплата'
            ],
        ];
        if(!isset($templates[$templateName])){
            throw new NotFoundHttpException("Page not found, sorry");
        }
        $page = $templates[$templateName]['page'];
        $template = $templates[$templateName]['template'];
        $title = $templates[$templateName]['title'];
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname'=>$page
        ]);
        $arr=[
            'main'=>[
                'parameters'=>[],
                'title'=>'главная'
            ],
            'last'=>$seo->getTitle()
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $form=null;
        if($page=='contacts'){
            $object = new Contacts();
            $form = $this->createForm(ContactsType::class,$object,[
                'action'=>$this->get('router')->generate('site_frontend_contacts')
            ]);
            if($request->isMethod('POST')){
                $form->handleRequest($request);
                if($form->isValid()){
                    $data = $form->getData();
                    $em->persist($data);
                    $em->flush();
                    return $this->render('SiteFrontendBundle:Default:contactsSuccess.html.twig',[
                        'title'=>$seo->getTitle(),
                        'breadcrumbs'=>$menu,
                    ]);
                }
            }
        }
        ($form)?$form=$form->createView():'';
        $sctaticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage($page);
        return $this->render($template,[
            'staticContent'=>$sctaticContent,
            'form'=>$form,
            'title'=>$seo->getTitle(),
            'breadcrumbs'=>$menu,
            'seo'=>$seo
        ]);
    }
}
