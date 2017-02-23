<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 4/28/2016
 * Time: 12:51 PM
 */
namespace Site\BackendBundle\Exceptions;

use Site\UserBundle\SiteUserBundle;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExceptionListener
{
    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function onKernelException($event)
    {
        $exception = $event->getException();
        if($exception instanceof \Site\BackendBundle\Exceptions\ItemNotValidException){
            $content = $this->container->get('templating')->renderResponse(
                'SiteBackendBundle:LoadXml:itemNotValid.html.twig',
                array(
                    'admin_pool'=> $this->container->get('sonata.admin.pool'),
                    'text'=>$exception->getMessage()
                )
            );
            $event->setResponse($content);
        }
        else if($exception instanceof \Site\BackendBundle\Exceptions\ToLongStringException){
            $content = $this->container->get('templating')->renderResponse(
                'SiteBackendBundle:LoadXml:itemNotValid.html.twig',
                array(
                    'admin_pool'=> $this->container->get('sonata.admin.pool'),
                    'text'=>$exception->getMessage()
                )
            );
            $event->setResponse($content);
        }
        else if($exception instanceof \Site\BackendBundle\Exceptions\XmlNotValidException){
            $content = $this->container->get('templating')->renderResponse(
                'SiteBackendBundle:LoadXml:xmlNotValid.html.twig',
                array(
                    'admin_pool'=> $this->container->get('sonata.admin.pool'),
                    'errors'=>$exception->getErrors()
                )
            );
            $event->setResponse($content);
        }
    }
}