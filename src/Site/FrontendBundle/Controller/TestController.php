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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\FrontendBundle\Form\CommentType;

class TestController extends Controller
{
//    public function testAction(){
//        $em=$this->getDoctrine()->getManager();
//        $products = $em->getRepository('SiteBackendBundle:Product')->findAll();
//        foreach ($products as $item){
//            $code = $item->getCod();
//            $code = trim(mb_strtolower($code,'UTF-8'));
//            $state = $item->getState();
//            $state = trim(mb_strtolower($state,'UTF-8'));
//            $item->setCod($code);
//            $item->setState($state);
//            $em->persist($item);
//        }
//        $sets = $em->getRepository('SiteBackendBundle:Set')->findAll();
//        foreach ($sets as $item){
//            $code = $item->getCod();
//            $code = trim(mb_strtolower($code,'UTF-8'));
//            $state = $item->getState();
//            $state = trim(mb_strtolower($state,'UTF-8'));
//            $item->setCod($code);
//            $item->setState($state);
//            $em->persist($item);
//        }
//        return new Response('ok');
//    }
//    public function watermarkAction(Request $request){
//        $em = $this->getDoctrine()->getManager();
//        $products = $em->getRepository('SiteBackendBundle:Product')->findAll();
//        $paginator = $this->get('knp_paginator');
//        $products = $paginator->paginate(
//            $products,
//            $request->query->get('page',1),
//            10
//        );
//        if(count($products)){
//            foreach($products as $product){
//                $this->performWotermarkGeneration($product,'poster','product_icon');
//                if(count($gallery=$product->getProductGallery())){
//                    foreach($gallery as $image){
//                        $this->performWotermarkGeneration($image,'image','product_gallery_icon');
//                    }
//                }
//            }
//        }
//        $em->flush();
//        $data = $products->getPaginationData();
//        $text = 'finished';
//        if($data['last']>$data['current']){
//            $url = $this->get('router')->generate('site_frontend_test',['page'=>$data['next']]);
//            $text ="<a href='".$url."'>Next</a>";
//        }
//        return new Response($text);
//    }
//    public function setWatermarkAction(Request $request){
//        $em = $this->getDoctrine()->getManager();
//        $sets = $em->getRepository('SiteBackendBundle:Set')->findAll();
//        $paginator = $this->get('knp_paginator');
//        $sets = $paginator->paginate(
//            $sets,
//            $request->query->get('page',1),
//            10
//        );
//        if(count($sets)){
//            foreach($sets as $set){
//                $this->performWotermarkGeneration($set,'poster','set_icon');
//                if(count($gallery=$set->getSetGallery())){
//                    foreach($gallery as $image){
//                        $this->performWotermarkGeneration($image,'image','set_gallery_icon');
//                    }
//                }
//            }
//        }
//        $em->flush();
//        $data = $sets->getPaginationData();
//        $text = 'finished';
//        if($data['last']>$data['current']){
//            $url = $this->get('router')->generate('site_frontend_test_set',['page'=>$data['next']]);
//            $text ="<a href='".$url."'>Next</a>";
//        }
//        return new Response($text);
//    }
//    private function performWotermarkGeneration($object,$item,$type){
//        $siteWebDir = $this->getParameter('site_upload.web_dir');
//        $tempUploadDir = $this->getParameter('site_upload.temp_upload_dir');
//        $rootDir = $this->get('kernel')->getRootDir();
//        $em = $this->getDoctrine()->getManager();
//        $getter = 'get'.ucfirst($item);
//        $setter = 'set'.ucfirst($item);
//        $handler = $this->get('upbeat_file_upload.handler');
//        $subDir='/'.$object->getId();
//        $file=[];
//        $image = $object->$getter();
//        $path = json_decode($image,true)['default_file'];
//        $fileAttr=pathinfo($path);
//        if(!$path||!isset($fileAttr['extension'])){
//            return;
//        }
//        $uploadDir = $rootDir . '/../'.$siteWebDir.'/'.$tempUploadDir.'/'.date("ymd");
//
//        if (!is_dir($uploadDir))
//            mkdir($uploadDir, 0777, true);
//
//        $fullPath=$rootDir.'/../'.$siteWebDir.$path;
//        $name='/'.$tempUploadDir.'/'.date("ymd").'/'.uniqid().'.'.$fileAttr['extension'];
//        copy($fullPath, $rootDir.'/../'.$siteWebDir.$name);
//        $file=[
//            'file_type'=>$type,
//            'path'=>$name
//        ];
//        $resultRes = $handler->handleFileAndSave($file,$subDir,false);
//        $object->$setter(json_encode($resultRes));
//        $em->persist($object);
//    }
}
