<?php

namespace Site\UploadBundle\Services;

use Imagick;
use RecursiveIteratorIterator;

/**
 * Class FileHandler
 * @package Site\UploadBundle\Services
 * methods:
 * cropImage - simple crop for jcrop
 * handleFileAndSave - make thumbnails from config,save files
 * handleCoverImageFile - composite`s image with cover pattern
 * makePerspective - make perspective view of image
 * makeCoverLeftPerspectiveThumbnail - make perspective image with front side
 * clearUploadDir - clear upload directory
 */
class FileHandler
{
    protected $session;
    protected $config;
    protected $sessionAttr;
    protected $uploadTempDir;
    protected $webDir;
    protected $rootDir;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param $config
     * @param $uploadDir
     * @param $webDir
     * @param $rootDir
     */
    public function __construct(\Symfony\Component\HttpFoundation\Session\SessionInterface $session, $config,$uploadDir,$webDir,$rootDir)
    {
        $this->session = $session;
        $this->config = $config;
        $this->webDir = $webDir;
        $this->uploadTempDir=$uploadDir;
        $this->rootDir=realpath($rootDir);
    }

    /**
     * crop image and save to temp directory
     * @param $path
     * @param $arr
     * return path to file
     * @return string
     */
    public function cropImage($path,$arr)
    {
        $file=realpath($this->rootDir).'/../'.$this->webDir.'/'.$path;
        $image=new Imagick($file);
        $image->cropImage((integer)$arr["width"],(integer)$arr['height'],(integer)$arr['x'],(integer)$arr['y']);
        $image->setImageFormat('png');
        $name='/'.$this->uploadTempDir.'/'.date("ymd").'/crop_'.uniqid().'.png';
        $image->writeImage(realpath($this->rootDir.'/../'.$this->webDir).'/'.$name);
        return $name;
    }

    /**
     * handle files in order to config
     * @param $field - field name
     * @param $uploadDir - directory like /uploads/products/1/
     * @param bool $json
     * @return array|bool|string
     * @throws \Exception
     */
    public function handleFileAndSave($file,$subDir, $json = true)
    {
        if($json){
            $file=json_decode($file,true);
            $file=json_decode(reset($file),true);
        }
        $filePath=$file['path'];
        $fieldType=$file['file_type'];
        $uploadDir=$this->config[$fieldType]['upload_dir'];
        $dir=$uploadDir.$subDir;
        if ($this->config[$fieldType]['type'] == 'file') {
                $result = $this->saveFile($filePath, $dir);
            }
        elseif ($this->config[$fieldType]['type'] == 'image') {
                $result = $this->saveImage($filePath, $dir, $this->config[$fieldType]['thumbnails']);
            }
            else {
                throw new \Exception('Unrecognized file type!');
            }
            return $result;
    }

    /**
     * handle files in order to config
     * @param $field - content of base64file
     * @param $uploadDir - directory like /uploads/products/1/
     * @param bool $json
     * @return array|bool|string
     * @throws \Exception
     */
    public function handleBaseFileAndSave($file,$fieldType,$subDir)
    {
        $img = new Imagick();
        $decoded = base64_decode($file);
        $img->readimageblob($decoded);
        $fileName = uniqid();
        $dir=$this->config[$fieldType]['upload_dir'];
        $path=$this->rootDir.'/../'.$this->webDir.$dir;
        $fullPath = $path.'/'.$subDir.'/'.$fileName.'.jpg';
        $this->checkDir($path.'/'.$subDir);
        $img->writeImage($fullPath);
        $result = array();
        $result['default_file'] = $dir.'/'.$subDir.'/'.$fileName.'.jpg';
        return $result;
    }

    /**
     * handle big files in order to config
     * @param $field - field name
     * @param $uploadDir - directory like /uploads/products/1/
     * @param bool $json
     * @return array|bool|string
     * @throws \Exception
     */
    public function handleBigFileAndSave($file,$subDir, $json = true)
    {
        if($json){
            $file=json_decode($file,true);
            $file=json_decode(reset($file),true);
        }

        $filePath=$file['path'];
        $fieldType=$file['file_type'];

        $uploadDir=$this->config[$fieldType]['upload_dir'];
        $dir=$uploadDir.$subDir;

        if ($this->config[$fieldType]['type'] == 'file') {
            $result = $this->saveFile($filePath, $dir);
        }
        elseif ($this->config[$fieldType]['type'] == 'image') {
            $result = $this->saveImage($filePath, $dir, $this->config[$fieldType]['thumbnails']);
        }
        elseif  ($this->config[$fieldType]['type'] == 'video') {
            $result = $this->saveVideoFile($filePath, $dir);
        }
        else {
            throw new \Exception('Unrecognized file type!');
        }
        return $result;
    }
    /**
     * clear upload directory,defined in config, except current day folder
     */
    public function clearUploadDir()
    {
        $dir = $this->rootDir.'/../'.$this->webDir.'/'.$this->uploadTempDir;
        if (!is_dir($dir)){
            return;
        }
        $arr = scandir($dir);
        foreach($arr as $item){
            if(!preg_match('/^\.(.*)?$/',$item)&&preg_match('/^(.+)\.(.*)?$/',$item)){
                $path = realpath($dir."/".$item);
                unlink($path);
            }
            elseif(!preg_match('/^\.(.*)?$/',$item)&&($item != date("ymd"))){
                $path = '/'.$this->uploadTempDir.'/'.$item;
                $this->clearDirectory($path,true);
            }
        }
    }
    public function clearDirectory($dir,$full=false)
    {
        $dir = $this->rootDir.'/../'.$this->webDir.$dir;
        if (!is_dir($dir)){
            return;
        }
        $it = new \RecursiveDirectoryIterator($dir);
        $files = new \RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        if($full){
            rmdir($dir);
        }
    }
    /**
     * @param $dir
     */
    private function checkDir($dir)
    {
        if (!is_dir($dir))
            mkdir($dir, 0777, true);
        chmod($dir, 0777);

    }
    /**
     * $arr['im_width']
     * $arr['im_height']
     * $arr['th_width']
     * $arr['th_height']
     * @param array $arr
     * resize parameters: width and height
     * @return array $sizes
     */
    private function getCropResizeParameters($arr)
    {
        $coeff=$arr['im_height']/$arr['im_width'];
        $resize=array();
        if(($arr['th_width']<$arr['im_width'])&&($arr['th_height']>$arr['im_height'])){
            $resize['height']=$arr['th_height'];
            if($coeff>1){
                $resize['width']=$coeff*$arr['th_height'];
            }
            else{
                $resize['width']=$arr['th_height']/$coeff;
            }
        }
        elseif(($arr['th_width']>$arr['im_width'])&&($arr['th_height']<$arr['im_height'])){
            $resize['width']=$arr['th_width'];
            if($coeff>1){
                $resize['height']=$arr['th_width']*$coeff;
            }
            else{
                $resize['height']=$arr['th_width']/$coeff;
            }
        }
        elseif(($arr['th_width']<$arr['im_width'])&&($arr['th_height']>$arr['im_height'])){
            $size=max($arr['th_width'],$arr['th_height']);
            if($coeff>1){
                $resize['height']=$size*$coeff;
                $resize['width']=$size;
            }
            else{
                $resize['height']=$size;
                $resize['width']=$size*$coeff;
            }
        }
        return $resize;
    }

    /**
     * simple save files
     * @param $path - path to file
     * @param $dir - where to save file
     * @return array
     */
    private function saveFile($path, $dir)
    {
        $fullDirPath=$this->rootDir.'/../'.$this->webDir.$dir;
        $fullPath=$this->rootDir.'/../'.$this->webDir.$path;
        $this->checkDir($fullDirPath);
        $result = array();
        $fileAttr=pathinfo($fullPath);
        $fileName = uniqid();
        rename ($fullPath, $fullDirPath.'/'.$fileName.'.'.$fileAttr['extension']);
        $result[] = $dir.'/'.$fileName.'.'.$fileAttr['extension'];
        return $result;
    }

    /**
     * simple save files
     * @param $path - path to file
     * @param $dir - where to save file
     * @return array
     */
    private function saveVideoFile($path, $dir)
    {
        $fullDirPath=$this->rootDir.'/../'.$this->webDir.$dir;
        $fullPath=$this->rootDir.'/../'.$this->webDir.$path;
        $this->checkDir($fullDirPath);
        $result = array();
        $fileAttr=pathinfo($fullPath);
        $fileName = uniqid();
        rename ($fullPath, $fullDirPath.'/'.$fileName.'.'.$fileAttr['extension']);
        $result['default_file'] = $dir.'/'.$fileName.'.'.$fileAttr['extension'];
        return $result;
    }

    /**
     * @param $filePath
     * @param $destinationDir
     * @param $thumbs
     * @return array
     * @throws \ErrorException
     * @throws \Exception
     */
    private function saveImage($filePath, $destinationDir, $thumbs)
    {
        $fullDestDir=$this->rootDir.'/../'.$this->webDir.$destinationDir;
        $this->checkDir($fullDestDir);
        $result = array();
        $fullPath=$this->rootDir.'/../'.$this->webDir.$filePath;
        (@get_headers($filePath)[0] == 'HTTP/1.1 200 OK')?$remoteFlag=true:$remoteFlag=false;
        if($remoteFlag){
            $fullPath = $filePath;
        }
        elseif(!file_exists($fullPath)){
            throw new \Exception('FileHandler::SaveImage - no file present');
        }
        $fileAttr=pathinfo($fullPath);
        copy($fullPath, $fullDestDir.'/'.$fileAttr['basename']);
        $result['default_file'] = $destinationDir.'/'.$fileAttr['basename'];
        if(!$remoteFlag){
            unlink($fullPath);
        }
        $fullPath=$this->rootDir.'/../'.$this->webDir.$result['default_file'];
        foreach ($thumbs as $key => $thumb)
        {
        if (isset($thumb['action']) == true) {
            $img=$this->performResize($fullPath,$thumb['action'],$thumb['width'],$thumb['height']);
            }
        if (isset($thumb['watermark']) == true) {
            $watermark= new Imagick(__DIR__.'/../Resources/public/images/watermarks/'.$thumb['watermark']);
            $watermark->setImageFormat('png');
            $watermark->setImageOpacity($thumb['opacity']);
            $paddingX=$img->getImageWidth()-$watermark->getImageWidth()-$thumb['padding-x'];
            $paddingY=$img->getImageHeight()-$watermark->getImageHeight()-$thumb['padding-y'];
            $img->compositeImage($watermark, imagick::COMPOSITE_OVER, $paddingX, $paddingY);
            }
        $this->checkDir($fullDestDir);
        $name = $key.uniqid().'.png';
        $path=$fullDestDir.'/'.$name;
        $img->writeImage($path);
        $result[$key] = $destinationDir.'/'.$name;
        }
        return $result;
    }

    /**
     * @param $path
     * @param $action
     * @param $width
     * @param $height
     * @return Imagick
     * @throws \ErrorException
     */
    private function performResize($path,$action,$width,$height)
    {
        $img= new Imagick($path);
        $img->setImageFormat('png');
        if(!$img->getImageHeight()){
            throw new \ErrorException('Error while file handling');
        }
        switch ($action) {
            case "exact_resize":
                $img->resizeImage($width, $height,Imagick::FILTER_LANCZOS,1,true);
                break;
            case "landscape_resize":
                $img->resizeImage($width, null,Imagick::FILTER_LANCZOS,1,true);
                break;
            case "portrait_resize":
                $img->resizeImage(null, $height,Imagick::FILTER_LANCZOS,1,true);
                break;
            case "exact_crop":
                if($img->getImageWidth()<$width||$img->getImageHeight()<$height){
                    $arr=array();
                    $arr['th_height']=$height;
                    $arr['th_width']=$width;
                    $arr['im_width']=$img->getImageWidth();
                    $arr['im_height']=$img->getImageHeight();
                    $resize=$this->getCropResizeParameters($arr);
                    $img->resizeImage($resize['width'], $resize['height'],Imagick::FILTER_LANCZOS,1);
                }
                $img->cropImage($width, $height,0,0);
                break;
            default:
                $img->resizeImage($width, $height,Imagick::FILTER_LANCZOS,1);
                break;
        }
        return $img;
    }
}