<?php

namespace Site\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Site\BackendBundle\Entity\Comment;
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
use Site\FrontendBundle\Form\CommentType;

class DefaultController extends Controller
{
    private $cartSession = 'cart_session';
    private $newCartSession = 'order_cart_session';

    public function sitemapAction(Request $request)
    {
        $em = $this->getDoctrine();

        $urls = [];
        $hostname = $request->getSchemeAndHttpHost();

        $urls[] = array('loc' => $this->get('router')->generate('site_frontend_homepage'), 'changefreq' => 'weekly', 'priority' => '1.0');
        $urls[] = array('loc' => $this->get('router')->generate('site_frontend_set_list'), 'changefreq' => 'weekly', 'priority' => '1.0');

        foreach ($em->getRepository('SiteBackendBundle:Set')->findAll() as $item) {
            $urls[] = array('loc' => $this->get('router')->generate('site_frontend_set_show',
                array('slug' => $item->getSlug())), 'priority' => '1.0');
        }

        $urls[] = array('loc' => $this->get('router')->generate('site_frontend_news_list'), 'changefreq' => 'weekly', 'priority' => '1.0');

        foreach ($em->getRepository('SiteBackendBundle:News')->findAll() as $item) {
            $urls[] = array('loc' => $this->get('router')->generate('site_frontend_news_show',
                array('slug' => $item->getSlug())), 'priority' => '1.0');
        }

        foreach ($em->getRepository('SiteBackendBundle:NewsTag')->findAll() as $item) {
            $urls[] = array('loc' => $this->get('router')->generate('site_frontend_news_tag_list',
                array('slug' => $item->getSlug())), 'priority' => '1.0');
        }

        foreach ($em->getRepository('SiteBackendBundle:Category')->findAll() as $item) {
            $urls[] = array('loc' => $this->get('router')->generate('site_frontend_category',
                array('slug' => $item->getSlug())), 'priority' => '1.0');
        }

        foreach ($em->getRepository('SiteBackendBundle:SubCategory')->findAll() as $item) {
            $urls[] = array('loc' => $this->get('router')->generate('site_frontend_sub_category',
                array(
                    'catSlug' => $item->getCategory()->getSlug(),
                    'subSlug' => $item->getSlug()
                )), 'priority' => '1.0');
        }

        foreach ($em->getRepository('SiteBackendBundle:Product')->findAll() as $item) {
            $urls[] = array('loc' => $this->get('router')->generate('site_frontend_product_show',
                array('slug' => $item->getSlug())), 'priority' => '1.0');
        }

        $urls[] = array('loc' => $this->get('router')->generate('site_frontend_about_company'), 'changefreq' => 'weekly', 'priority' => '1.0');
        $urls[] = array('loc' => $this->get('router')->generate('site_frontend_contacts'), 'changefreq' => 'weekly', 'priority' => '1.0');
        $urls[] = array('loc' => $this->get('router')->generate('site_frontend_about_delivery'), 'changefreq' => 'weekly', 'priority' => '1.0');
        $urls[] = array('loc' => $this->get('router')->generate('site_frontend_about_payment'), 'changefreq' => 'weekly', 'priority' => '1.0');
        $urls[] = array('loc' => $this->get('router')->generate('site_frontend_static_reviews'), 'changefreq' => 'weekly', 'priority' => '1.0');

        return $this->render('SiteFrontendBundle:Parts:sitemap.xml.twig', [
            'urls' => $urls, 'hostname' => $hostname
        ]);
    }

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productNovels = $em->getRepository('SiteBackendBundle:Product')->getLastByTagAndNumber(5, 'новинка');
        $setNovels = $em->getRepository('SiteBackendBundle:Set')->getLastByTagAndNumber(5, 'новинка');
        $productHits = $em->getRepository('SiteBackendBundle:Product')->getLastByTagAndNumber(5, 'топ продаж');
        $setHits = $em->getRepository('SiteBackendBundle:Set')->getLastByTagAndNumber(5, 'топ продаж');
        $novels = $this->bestFiveByRand($productNovels, $setNovels);
        $hits = $this->bestFiveByRand($productHits, $setHits);
        $news = $em->getRepository('SiteBackendBundle:News')->getLastByNumber(4);
        $slides = $em->getRepository('SiteBackendBundle:Slider')->getLastByNumber(10);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('main_page');
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname' => 'main'
        ]);
        return $this->render('SiteFrontendBundle:Default:index.html.twig', [
            'novels' => $novels,
            'hits' => $hits,
            'news' => $news,
            'slides' => $slides,
            'staticContent' => $staticContent,
            'seo' => $seo
        ]);
    }

    /**
     * for main page popular and hot blocks
     *
     * @param $products
     * @param $sets
     * @return array
     */
    private function bestFiveByRating($products, $sets)
    {
        if (!is_array($products)) $products = [];
        if (!is_array($sets)) $sets = [];
        $arr = array_merge($products, $sets);
        if (count($arr) <= 5) return $arr;
        $temp = [];
        foreach ($arr as $k => $item) {
            $temp[$k] = $item->getRating();
        }
        asort($temp);
        $delete = array_slice($temp, 0, count($temp) - 5);
        foreach ($delete as $k => $value) {
            unset($arr[$k]);
        }
        return $arr;
    }

    private function bestFiveByRand($products, $sets)
    {
        if (!is_array($products)) $products = [];
        if (!is_array($sets)) $sets = [];
        $arr = array_merge($products, $sets);
        if (count($arr) <= 5) return $arr;
        $keys = array_rand($arr, 5);
        foreach ($arr as $k => $value) {
            if (!in_array($k, $keys)) {
                unset($arr[$k]);
            }
        }
        return $arr;
    }

    public function headerMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SiteBackendBundle:Category')->getCategoriesWithSubCategoriesIndexBySlug();
        $itemsNumber = $this->get('fonmaxx.cart.items.number')->getItemsNumber($this->newCartSession);
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('footer_and_header');
        $searchForm = $this->createForm(SearchType::class, [], [
            'action' => $this->get('router')->generate('site_frontend_search')
        ]);
        return $this->render('SiteFrontendBundle:Parts:_header.html.twig', [
            'category' => new Category(),
            'categories' => $categories,
            'itemsNumber' => $itemsNumber,
            'staticContent' => $staticContent,
            'searchForm' => $searchForm->createView()
        ]);
    }

    public function footerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SiteBackendBundle:Category')->getCategoriesWithSubCategoriesIndexBySlug();
        $staticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage('footer_and_header');
        return $this->render('SiteFrontendBundle:Parts:_footer.html.twig', [
            'categories' => $categories,
            'staticContent' => $staticContent
        ]);
    }

    public function getCallbackFormAction(Request $request)
    {
        $callback = new Callback();
        $form = $this->createForm(CallbackType::class, $callback, [
            'action' => $this->get('router')->generate('site_frontend_receive_callback')
        ]);
        return $this->render('SiteFrontendBundle:Form:_callback_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function receiveCallbackAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $callback = new Callback();
        $form = $this->createForm(CallbackType::class, $callback, [
            'action' => $this->get('router')->generate('site_frontend_receive_callback')
        ]);
        $form->handleRequest($request);
        $response = [
            'success' => false,
            'message' => ''
        ];
        if ($form->isValid()) {
            $callback = $form->getData();
            $em->persist($callback);
            $em->flush();
            $this->sendMail($callback, 'callback');
            $response = [
                'success' => true,
                'message' => 'Мы свяжемся с вами в ближайшее время'
            ];
        }
        return new JsonResponse(json_encode($response));
    }

    public function staticAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $templateName = $request->get('_route');
        $templates = [
            'site_frontend_about_company' => [
                'template' => 'SiteFrontendBundle:Default:about_company.html.twig',
                'page' => 'about_company',
                'title' => 'О нас'
            ],
            'site_frontend_contacts' => [
                'template' => 'SiteFrontendBundle:Default:contacts.html.twig',
                'page' => 'contacts',
                'title' => 'Контакты'
            ],
            'site_frontend_about_delivery' => [
                'template' => 'SiteFrontendBundle:Default:about_delivery.html.twig',
                'page' => 'about_delivery',
                'title' => 'Доставка'
            ],
            'site_frontend_about_payment' => [
                'template' => 'SiteFrontendBundle:Default:about_payment.html.twig',
                'page' => 'about_payment',
                'title' => 'Оплата'
            ],
            'site_frontend_static_reviews' => [
                'template' => 'SiteFrontendBundle:Default:static_reviews.html.twig',
                'page' => 'static_reviews',
                'title' => 'Отзывы'
            ],
        ];
        if (!isset($templates[$templateName])) {
            throw new NotFoundHttpException("Page not found, sorry");
        }
        $page = $templates[$templateName]['page'];
        $template = $templates[$templateName]['template'];
        $title = $templates[$templateName]['title'];
        $seo = $em->getRepository('SiteBackendBundle:StaticSeoPages')->findOneBy([
            'linkname' => $page
        ]);
        $arr = [
            'main' => [
                'parameters' => [],
                'title' => 'главная'
            ],
            'last' => $seo->getTitle()
        ];
        $breadcrumbsGenerator = $this->get('fonmaxx.breadcrumbs.generator');
        $menu = $breadcrumbsGenerator->generateMenu($arr);
        $form = null;
        $comments = [];
        if ($page == 'contacts') {
            $object = new Contacts();
            $form = $this->createForm(ContactsType::class, $object, [
                'action' => $this->get('router')->generate('site_frontend_contacts')
            ]);
            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();
                    $em->persist($data);
                    $em->flush();
                    $this->sendMail($data, 'contacts');
                    return $this->render('SiteFrontendBundle:Default:contactsSuccess.html.twig', [
                        'title' => $seo->getTitle(),
                        'breadcrumbs' => $menu,
                    ]);
                }
            }
        } else if ($page == 'static_reviews') {
            $comment = new Comment();
            $comment->setPageUrl($request->getPathInfo());
            $comments = $em->getRepository('SiteBackendBundle:Comment')->getCommentsByStateAndPath('approved', $request->getPathInfo());
            $form = $this->createForm(CommentType::class, $comment, [
                'action' => $this->get('router')->generate('site_frontend_add_comment', [
                    'type' => 'review'
                ])
            ]);
        }
        if ($form) $form = $form->createView();
        $sctaticContent = $em->getRepository('SiteBackendBundle:StaticPageContent')->getStaticContentForPage($page);
        return $this->render($template, [
            'staticContent' => $sctaticContent,
            'form' => $form,
            'title' => $seo->getTitle(),
            'breadcrumbs' => $menu,
            'seo' => $seo,
            'comments' => $comments
        ]);
    }

    private function sendMail($entity, $type)
    {
        $arr = [
            'contacts' => [
                'template' => 'SiteFrontendBundle:EMails:_contactsMail.html.twig',
                'subject' => 'Новый запрос о контактах с сайта Uvelife.com'
            ],
            'callback' => [
                'template' => 'SiteFrontendBundle:EMails:_callbackMail.html.twig',
                'subject' => 'Новый запрос о перезвоне с сайта Uvelife.com'
            ],
        ];
        if (!isset($arr[$type])) {
            return;
        }
        $parameters = $this->container->getParameter('mailer_parameters');
        $message = \Swift_Message::newInstance()
            ->setSubject($arr[$type]['subject'])
            ->setFrom($parameters['send_from'])
            ->setBody(
                $this->renderView($arr[$type]['template'], ['entity' => $entity]),
                'text/html'
            );
        try {
            foreach ($parameters['send_to'] as $to) {
                $message->setTo($to);
                $this->get('mailer')->send($message);
            }
        } catch (\Swift_TransportException $e) {

        }
    }

    /**
     * Load comment or review to db
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addCommentAction(Request $request, $type)
    {
        $types = ['review', 'comment'];
        $recapthaValidator = $this->get('fonmaxx.recaptcha.validate');
        $comment = new Comment();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CommentType::class, $comment, []);
        $form->handleRequest($request);
        $result = [
            'success' => false
        ];
        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            $recapthaValidator->captchaverify($request->get('g-recaptcha-response')) &&
            in_array($type, $types)
        ) {
            $state = ($comment->states['новый']) ? $comment->states['новый'] : null;
            $comment->setState($state);
            $url = $comment->getPageUrl();
            if ($this->checkUrl($url)) {
                $comment->setType($type);
                $em->persist($comment);
                $em->flush();
                $result = [
                    'success' => true
                ];
            }
        }
        return new JsonResponse(json_encode($result));
    }

    private function checkUrl($url)
    {
        $route = $this->get('router')->match($url);
        return (
            $route['_route'] == 'site_frontend_static_reviews' ||
            $route['_route'] == 'site_frontend_product_show' ||
            $route['_route'] == 'site_frontend_set_show'
        );
    }
}
