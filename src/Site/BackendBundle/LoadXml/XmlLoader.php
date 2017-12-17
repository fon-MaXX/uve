<?php

namespace Site\BackendBundle\LoadXml;
use Doctrine\Common\Collections\ArrayCollection;
use Site\BackendBundle\Entity\ChainSize;
use Site\BackendBundle\Exceptions\ItemNotValidException;
use Site\BackendBundle\Exceptions\NoCityException;
use Site\BackendBundle\Exceptions\XmlNotValidException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Site\BackendBundle\Entity\Product;
use Site\BackendBundle\Entity\Set;
use Site\BackendBundle\Entity\RingSize;
use Site\BackendBundle\Entity\InsertionColor;
class XmlLoader
{
    protected $container;
    protected $objectTypeIndex = null;
    protected $objectCodIndex = null;
    protected $em;
    protected $products = [];
    protected $sets = [];
    protected $ringSizes = [];
    protected $insertionColors = [];
    protected $shareTags = [];
    protected $categories = [];
    public function __construct(ContainerInterface $container)
    {
        $this->container =$container;
        $this->em = $this->container->get('doctrine')->getManager();
        $this->products = $this->em->getRepository("SiteBackendBundle:Product")->getProductsIndexByCode();
        $this->freshProducts = $this->products;
        $this->sets = $this->em->getRepository("SiteBackendBundle:Set")->getSetsIndexByCode();
        $this->freshSets = $this->sets;
        $this->ringSizes = $this->em->getRepository("SiteBackendBundle:RingSize")->getRingSizesIndexByTitle();
        $this->chainSizes = $this->em->getRepository("SiteBackendBundle:ChainSize")->getChainSizesIndexByTitle();
        $this->insertionColors = $this->em->getRepository("SiteBackendBundle:InsertionColor")->getInsertionColorsIndexByTitle();
        $this->shareTags = $this->em->getRepository("SiteBackendBundle:ShareTag")->getShareTagsIndexByTitle();
        $this->categories = $this->em->getRepository("SiteBackendBundle:Category")->getCategoriesWithSubCategoriesIndexByTitle();
        $this->subCategories = $this->em->getRepository("SiteBackendBundle:SubCategory")->getSubCategoriesWithCategoriesIndexByTitle();
    }

    /**
     * enter-point for parsing
     *
     * @param $path
     * @return bool
     * @throws ItemNotValidException
     * @throws XmlNotValidException
     */
    public function parseXmlFile($path)
    {
        if(!file_exists($path)){
            return false;
        }
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($path);
        if(!$xml){
            $errors = libxml_get_errors();
            $exception = new XmlNotValidException();
            $exception->setErrors($errors);
            throw  $exception;
        }
        $parameters = [
            "artikul"=>[
                'method'=>'setCod'
            ],
            "nazvanie"=>[
                'method'=>'setTitle'
            ],
            "opisanie"=>[
                'method'=>'setShortcut'
            ],
            "ves"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setWeight'
            ],
            "cena"=>[
                'method'=>'setPrice',
                'setter'=>'setPrice'
            ],
            "aksionnaya_cena"=>[
                'method'=>'setPrice',
                'setter'=>'setSharePrice'
            ],
            "metal"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setMetal'
            ],
            "tip_vstavki"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setInsertionType'
            ],
            "cvet_vstavki"=>[
                'method'=>'setInsertionColor'
            ],
            "forma_vstavki"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setInsertionShape'
            ],
            "parametru_vstavki"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setInsertionParameters'
            ],
            "parametru_izdeliya"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setProductParameters'
            ],
            "vid_pleteniya"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setWeavingType'
            ],
            "dlina_cepi"=>[
                'method'=>'setChainSizes'
            ],
            "rasmer_kolsa"=>[
                'method'=>'setRingSize'
            ],
            "pokrutie"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setCovering'
            ],
            "katalog"=>[
                'method'=>'setCategory'
            ],
            "tip_izdeliya"=>[
                'method'=>'setSubCategory'
            ],
            "tematika"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setTheme'
            ],
            "nabor"=>[
            ],
            "sostav_nabora"=>[
                'method'=>'setSetComposition'
            ],
            "status_izdeliya"=>[
                'method'=>'setState',
                'setter'=>'setState'
            ],
            "proizvoditel"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setManufacturer'
            ],
            "ves_izdeliya_/_reiting"=>[
                'method'=>'SetFilterAttribute',
                'setter'=>'setRating'
            ],
        ];
        $xml=$xml->Worksheet->Table;
        $test = ($xml->Row&&$xml->Row[0])?$xml->Row[0]:null;
        if(!($sortedParameters = $this->checkForValidParameters($test,$parameters)))
        {
            return false;
        }
        $size = count($xml->Row);
        $setsArray=null;
        for($i=1;$i<$size;$i++){
            if(count($xml->Row[$i]) == count($parameters)){
                $item = $xml->Row[$i];
                $type = (string)$item->Cell[$this->objectTypeIndex]->Data;
                $code = (string)$item->Cell[$this->objectCodIndex]->Data;
                $code = trim(mb_strtolower($code,'UTF-8'));
                if($type == "1"){
                    $setsArray[]=$item;
                }
                else{
                    $object = (isset($this->products[$code]))?$this->products[$code]:new Product();
                    $object = $this->loadItem($object,$sortedParameters,$item,'product');
                    $this->em->persist($object);
                    $this->products[$object->getCod()]=$object;
                }
            }
        }
        if(count($setsArray)){
            $arr=[];
            foreach($setsArray as $set){
                $code = (string)$set->Cell[$this->objectCodIndex]->Data;
                $code = trim(mb_strtolower($code,'UTF-8'));
                $object = (isset($this->sets[$code]))?$this->sets[$code]:new Set();
                $object = $this->loadItem($object,$sortedParameters,$set,'set');
                $this->em->persist($object);
                $arr[]=$object;
            }
        }
        if(count($this->freshProducts)){
            foreach($this->freshProducts as $item){
                $item->setIsFresh(false);
                $this->em->persist($item);
            }
        }
        if(count($this->freshSets)){
            foreach($this->freshSets as $item){
                $item->setIsFresh(false);
                $this->em->persist($item);
            }
        }
        $this->em->flush();
        $logger = $this->container->get('upbeat.xml.load.logger');
        $logger->logXmlLoad($path);
        return true;
    }

    /**
     * performs load for all setters
     *
     * @param $object
     * @param $sortedParameters
     * @param $row
     * @param $type
     * @return mixed
     */
    private function loadItem($object,$sortedParameters,$row,$type)
    {
        foreach($sortedParameters as $key=>$item){
            $text = (string)$row->Cell[$key]->Data;
            if(isset($item['method'])){
                $setter = (isset($item['setter']))?$item['setter']:null;
                $method = $item['method'];
                $object = $this->$method($object,$text,$type,$setter);
            }
        }
        return $this->unsetItemFromList($object,$type);
    }

    /**
     * unset all items which are present in new-file
     * @param $object
     * @param $type
     * @return mixed
     */
    private function unsetItemFromList($object,$type){
        $name = 'fresh'.ucfirst($type).'s';
        $list=&$this->$name;
        $cod = $object->getCod();
        if(count($list)&&isset($list[$cod])){
            unset($list[$cod]);
            $object->setIsFresh(true);
        }
        return $object;
    }
    /**
     * cod setter
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     * @throws ToLongStringException
     */
    private function setCod($object,$text,$type,$setter){
        if(strlen($text)>255){
            throw new ToLongStringException("Артикул ".$text." длиннее 255");
        }
        $code = trim(mb_strtolower((string)$text, 'UTF-8'));
        return $object->setCod($code);
    }

    /**
     * title setter
     *
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     * @throws ToLongStringException
     */
    private function setTitle($object,$text,$type,$setter){
        if(strlen($text)>255){
            throw new ToLongStringException("Название ".$text." длиннее 500");
        }
        return $object->setTitle($text);
    }
    /**
     * title setter
     *
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     * @throws ToLongStringException
     */
    private function setState($object,$text,$type,$setter){
        if(strlen($text)>255){
            throw new ToLongStringException("Название ".$text." длиннее 500");
        }
        $state = trim(mb_strtolower($text,'UTF-8'));
        return $object->setState($text);
    }

    /**
     * set shortcut field
     *
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     */
    private function setShortcut($object,$text,$type,$setter){
        if($type == 'product'&&$text!='-'&&$text){
            $object->setShortcut($text);
        }
        return $object;
    }

    /**
     * Sets all string properties of object(metal,theme etc)
     *
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     * @throws ToLongStringException
     */
    private function SetFilterAttribute($object,$text,$type,$setter)
    {

        if (strlen($text) > 255) {
            throw new ToLongStringException("Поле \"" . $text . "\" длиннее 255");
        }
        if($setter&&is_callable([$object,$setter])&&$text!='-'&&$text){
            $object->$setter($text);
        }
        return $object;
    }

    /**
     * Setter for all prices(regular and share)
     *
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     * @throws ToLongStringException
     */
    private function setPrice($object,$text,$type,$setter)
    {

        if (strlen($text) > 30) {
            throw new ToLongStringException("Цена \"" . $text . "\" длиннее 255");
        }
        if($type != 'product'){
            return $object;
        }
        if($text!='-'&&$text){
            $object->$setter($text);
            if($setter == "setSharePrice"){
                if(isset($this->shareTags['акция'])){
                    $tag = $this->shareTags['акция'];
                    if(!$object->hasShareTag($tag)){
                        $object->addShareTag($tag);
                    }
                }
            }
        }
        elseif($setter == "setSharePrice"){
            if(isset($this->shareTags['акция'])){
                $tag = $this->shareTags['акция'];
                if($object->hasShareTag($tag)){
                    $object->removeShareTag($tag);
                    $tag->removeProduct($object);
                }
            }
        }
        return $object;
    }

    /**
     * Setter for insertion color tags
     *
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     */
    private function setInsertionColor($object,$text,$type,$setter){
        if($type == 'product'&&$text!='-'&&$text){
            $items = explode(';',$text);
            if($object->getInsertionColors()&&count($object->getInsertionColors())){
                foreach($object->getInsertionColors() as $color){
                    if(!count($items)||!in_array($color->getTitle(),$items)){
                        $object->removeInsertionColor($color);
                        $color->removeProduct($object);
                    }
                }
            }
            if(count($items)){
                foreach($items as $item){
                    $item = trim(mb_strtolower($item, 'UTF-8'));
                    if(isset($this->insertionColors[$item])){
                        if(!$object->hasInsertionColor($this->insertionColors[$item])){
                            $object->addInsertionColor($this->insertionColors[$item]);
                        }
                    }
                    else if($item){
                        $color = new InsertionColor();
                        $color->setTitle($item);
                        $this->em->persist($color);
                        $this->insertionColors[$item] = $color;
                        $object->addInsertionColor($color);
                    }
                }
            }
        }
        elseif($type == 'product'&&$text=='-'&&$text){
            if(count($colors=$object->getInsertionColors())){
                foreach($colors as $color){
                    $object->removeInsertionColor($color);
                    $color->removeProduct($object);
                }
            }
        }
        return $object;
    }
    /**
     * Setter for chain size tags
     *
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     */
    private function setChainSizes($object,$text,$type,$setter){
        if($type == 'product'&&$text!='-'&&$text){
            $items = explode(';',$text);
            if($object->getChainSizes()&&count($object->getChainSizes())){
                foreach($object->getChainSizes() as $size){
                    if(!count($items)||!in_array($size->getTitle(),$items)){
                        $object->removeChainSize($size);
                        $size->removeProduct($object);
                    }
                }
            }
            if(count($items)){
                foreach($items as $item){
                    $item = trim($item);
                    if(isset($this->chainSizes[$item])){
                        if(!$object->hasChainSize($this->chainSizes[$item])){
                            $object->addChainSize($this->chainSizes[$item]);
                        }
                    }
                    else{
                        $size = new ChainSize();
                        $size->setTitle($item);
                        $this->em->persist($size);
                        $this->chainSizes[$item] = $size;
                        $object->addChainSize($size);
                    }
                }
            }
        }
        elseif($type == 'product'&&$text=='-'&&$text){
            if(count($sizes=$object->getChainSizes())){
                foreach($sizes as $size){
                    $object->removeChainSize($size);
                    $size->removeProduct($object);
                }
            }
        }
        return $object;
    }

    /**
     * Setter for ring size tags
     *
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     */
    private function setRingSize($object,$text,$type,$setter){
        if($type == 'product'&&$text!='-'&&$text){
            $items = explode(';',$text);
            if($object->getRingSizes()&&count($object->getRingSizes())){
                foreach($object->getRingSizes() as $size){
                    if(!count($items)||!in_array($size->getTitle(),$items)){

                        $object->removeRingSize($size);
                        $size->removeProduct($object);
                    }
                }
            }
            if(count($items)){
                foreach($items as $item){
                    $item = trim($item);
                    if(isset($this->ringSizes[$item])){
                        if(!$object->hasRingSize($this->ringSizes[$item])){
                            $object->addRingSize($this->ringSizes[$item]);
                        }
                    }
                    else{
                        $size = new RingSize();
                        $size->setTitle($item);
                        $this->em->persist($size);
                        $this->ringSizes[$item] = $size;
                        $object->addRingSize($size);
                    }
                }
            }
        }
        elseif ($type == 'product'&&$text=='-'&&$text){
            if(count($sizes=$object->getRingSizes())){
                foreach($sizes as $size){
                    $object->removeRingSize($size);
                    $size->removeProduct($object);
                }
            }
        }
        return $object;
    }
    private function setCategory($object,$text,$type,$setter){
        return $object;
    }
    private function setSubCategory($object,$text,$type,$setter){
        if($type!='set'&&isset($this->subCategories[$text])){
            $subCategory = $this->subCategories[$text];
            $object->setSubCategory($subCategory);
            $this->em->persist($object);
        }
        return $object;
    }

    /**
     * Adds products to Set
     * @param $object
     * @param $text
     * @param $type
     * @param $setter
     * @return mixed
     */
    private function setSetComposition($object,$text,$type,$setter){
        if($type == 'set'&&$text!='-'&&$text){
            $rowItems = explode(';',$text);
            $items=[];
            if(count($rowItems)){
                foreach ($rowItems as $item){
                    $items[]=trim(mb_strtolower($item,'UTF-8'));
                }
            }
            if($object->getProducts()&&count($object->getProducts())){
                foreach($object->getProducts() as $product){
                    if(!count($items)||!in_array($product->getCod(),$items)){
                        $object->removeProduct($product);
                        $product->removeSet($object);
                    }
                }
            }
            if(count($items)){
                foreach($items as $item){
                    if(isset($this->products[$item])){
                        if(!$object->hasProduct($this->products[$item])){
                            $object->addProduct($this->products[$item]);
                        }
                    }
                }
            }
        }
        return $object;
    }
    /**
     * Replaces all whitespaces with "_" and returnes lowercase string
     *
     * "Parametru vstavki" === "parametri_vstavki"
     *
     * @param $text
     * @return string
     */
    private function prepareParameter($text){
        $text = trim($text);
        $res = preg_replace('|[\s]+|s', ' ', $text);
        $res = str_replace(' ','_',$text);
        return strtolower($res);
    }
    /**
     * Checks xml structure of 1-st row,returns: sorted array of parameters||false
     *
     * @param $test
     * @param $parameters
     * @return array|bool|null
     */
    private function checkForValidParameters($test,$parameters)
    {
        if(!count($parameters)||!count($test)){
            return false;
        }
        $sortedParameters = null;
        $index = 0;
        foreach($test as $item){
            $value = $this->prepareParameter((string)$item->Data);
            if(!isset($parameters[$value])){
                return false;
            }
            if($value == 'nabor'){
                $this->objectTypeIndex = $index;
            }
            if($value == 'artikul'){
                $this->objectCodIndex = $index;
            }
            $sortedParameters[] = $parameters[$value];
            $index++;
        }
        return $sortedParameters;
    }
}