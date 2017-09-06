<?php

namespace Site\UploadBundle\Controller;
use Site\UploadBundle\Form\CropType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class DefaultController extends Controller
{
    private $uploadFile = null;

    public function uploadAction(Request $request)
    {
        $this->uploadFile = $request->files->get('file');
        if (is_null($this->uploadFile)) {
            die('File not found!');
        }

        $data = array(
            'success' => false,
            'error' => 'Upload error'
        );
        if ($this->uploadFile->isValid() && ($request->get('secure_token') === $this->get('session')->get('secure_token'))) {
            $filesConfig = $this->container->getParameter('site_upload.types');
            $fileSettings = $filesConfig[$request->get('type', 'default')];
            $sessionAttr = $request->get('field');
            $siteWebDir = $this->container->getParameter('site_upload.web_dir');
            $tempUploadDir = $this->container->getParameter('site_upload.temp_upload_dir');
            $validator = $this->getFileValidator($fileSettings);

            if (!$validator) {
                $data = array(
                    'success' => false,
                    'error' => 'Not valid file format'
                );

                return new JsonResponse($data);
            }

            $errorList = $this->get('validator')->validate($this->uploadFile, $validator);

            if (count($errorList) == 0) {

                $uploadDir = $this->get('kernel')->getRootDir() . '/../'.$siteWebDir.'/'.$tempUploadDir.'/'.date("ymd");

                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0777, true);

                $uniq = uniqid();

                $fileName = 'file'.$uniq.'.'.strtolower($this->uploadFile->getClientOriginalExtension());


                $this->uploadFile->move($uploadDir, $fileName);
                $arr=array();
                $arr['file_type']=$request->get('type');
                $arr['field']=$request->get('field');
                $arr['path']='/'.$tempUploadDir.'/'.date("ymd").'/'.$fileName;
                $data = array(
                    'success' => true,
                    'file' => json_encode($arr),
                    'name' => $this->uploadFile->getClientOriginalName(),
                    'uniq' => $uniq
                );
            } else {
                $data = array(
                    'success' => false,
                    'error' => $errorList[0]->getMessage()
                );
            }

        }

        return new JsonResponse($data);
    }
    public function cropAction(Request $request)
    {
        $params=$request->request->all();
        $form = $this->createForm(CropType::class);
        $form->submit($params);
        if($form->isValid()) {
            $properties = $form->getData();
            $arr['width'] = $properties['w'];
            $arr['height'] = $properties['h'];
            $arr['x'] = $properties['x'];
            $arr['y'] = $properties['y'];
            $fileHandler = $this->container->get('upbeat_file_upload.handler');
            $path = $fileHandler->cropImage($properties['path'], $arr);
            $arr=array();
            $arr['file_type']=$request->get('type');
            $arr['path']=$path;
            $arr['field']=$request->get('field');
            $data = array(
                'success' => true,
                'file' =>json_encode($arr)
            );
        }
        else{
            $data = array(
                'success' => false,
                'error' => (string) $form->getErrors(true, false)
                );
        }
        return new JsonResponse($data);
    }

    protected function getFileValidator($settings)
    {
        $fileConstraint = null;

        $formats = explode(",", $settings['format']);
        $match = false;

        foreach ($formats as $format) {
            if (strtolower($format) == strtolower($this->uploadFile->getClientOriginalExtension()))
                $match = true;
        }

        if (!$match)
            return false;

        if ($settings['type'] == 'file') {
            $fileConstraint = new File();
            $fileConstraint->maxSize = $settings['max_size'];
            $fileConstraint->mimeTypes = $settings['mime_type'];
        } elseif ($settings['type'] == 'image') {
            $fileConstraint = new Image();
            $fileConstraint->maxSize = $settings['max_size'];
        }

        if (is_null($fileConstraint))
            throw new \Exception('Not found file type in configuration!');
        return $fileConstraint;
    }
}