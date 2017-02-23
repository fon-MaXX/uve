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
}
