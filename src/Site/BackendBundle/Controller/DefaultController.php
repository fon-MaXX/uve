<?php

namespace Site\BackendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function userMenuAction()
    {
        return $this->render('SiteBackendBundle:Default:userMenu.html.twig', array());
    }
    public function renderTopBlocksAction(){
        $em = $this->getDoctrine()->getManager();
        $newOrders = $em->getRepository('SiteBackendBundle:Order')->getAllByState('new');
        $newCallbacks = $em->getRepository('SiteBackendBundle:Callback')->getAllByState('new');
        $newComments = $em->getRepository('SiteBackendBundle:Comment')->getAllByState('new');
        return $this->render('SiteBackendBundle:Parts:_top_blocks.html.twig',[
                'orders'=>count($newOrders),
                'callbacks'=>count($newCallbacks),
                'comments'=>count($newComments),
            ]);
    }
}
