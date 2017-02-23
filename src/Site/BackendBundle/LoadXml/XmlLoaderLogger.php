<?php


namespace Site\BackendBundle\LoadXml;
use Doctrine\Common\Collections\ArrayCollection;
use Site\BackendBundle\Entity\XmlLoadLog;
use Symfony\Component\DependencyInjection\ContainerInterface;

class XmlLoaderLogger
{
    protected $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container =$container;
    }
    public function logXmlLoad($path)
    {
        $em = $this->container->get('doctrine')->getManager();
        $file = $this->handleFile($path);
        $log = new XmlLoadLog();
        $log->setFile($file);
        $em->persist($log);
        $em->flush();
    }
    private function handleFile($path){
        $fileHandler = $this->container->get('upbeat_file_upload.handler');
        $dir = $this->container->getParameter('logger_save_dir');
        $kernelDir=$this->container->getParameter('kernel.root_dir');
        $name = date('Y_m_d_H_i_s',time()).'_'.rand(10,20).'.xml';
        $fullPath = $kernelDir.'/..'.$dir.$name;
        if (!is_dir($kernelDir.'/..'.$dir))
        {
            mkdir($kernelDir.'/..'.$dir, 0777, true);
        }
        chmod($kernelDir.'/..'.$dir, 0777);
        rename($path,$fullPath);
        $tempUploadDir=$this->container->getParameter('xml_temp_upload_dir');
        $fileHandler->clearDirectory($tempUploadDir);
        return $dir.$name;
    }
}