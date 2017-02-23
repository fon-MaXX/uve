<?php
namespace Site\BackendBundle\Controller;
use Site\BackendBundle\Entity\XmlLoad;
use Site\BackendBundle\Form\XmlLoadType;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class XmlLoaderController extends Controller
{
    public function loadXmlAction()
    {
        $items = new XmlLoad();
        $form = $this->createForm(XmlLoadType::class,$items);
        return $this->render("SiteBackendBundle:LoadXml:loadXml.html.twig", array(
            'action'     => 'list',
            'form'  =>  $form->createView()
        ), null);
    }
    public function submitLoadedXmlAction(Request $request)
    {
        $em = $this->container->get("doctrine")->getManager();
        $items = new XmlLoad();
        $form = $this->createForm(XmlLoadType::class,$items);
        $form->handleRequest($request);
        if($form->isValid()){
            $file = $items->getFile();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $tempUploadDir=$this->container->getParameter('xml_temp_upload_dir');
            $tempDir = $this->container->getParameter('kernel.root_dir').'/../web'.$tempUploadDir;

            $file->move($tempDir, $fileName);
            $loader = $this->get('upbeat.xml.loader');
            $loader->parseXmlFile($tempDir.'/'.$fileName);
            $message = "XML успешно загружен в базу";
            return $this->render("SiteBackendBundle:LoadXml:successXmlLoad.html.twig", array(
                'message'=>$message
            ), null);
        }
        return $this->render("SiteBackendBundle:LoadXml:loadXml.html.twig", array(
            'action'     => 'list',
            'form'  =>  $form->createView()
        ), null);
    }
    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

}