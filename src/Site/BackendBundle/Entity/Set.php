<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Set
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="set_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\SetRepository")
 */
class Set
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     *
     * @ORM\Column(name="title", type="string", length=500, nullable=true)
     */
    private $title;
    /**
     *
     * @ORM\Column(name="keywords", type="text", nullable=true)
     */
    private $keywords;
    /**
     *
     * @ORM\Column(name="description_field", type="string", length=256, nullable=true)
     */
    private $description;
    /**
     * @Gedmo\Slug(fields={"title"},unique=true,separator="-")
     * @ORM\Column(name="slug", length=255, unique=true)
     */
    private $slug;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="sets",cascade={"persist"})
     */
    private $products;
    /**
     *
     * @ORM\Column(name="cod_field", type="string", length=255, nullable=false, unique=true)
     */
    private $cod;
    /**
     *
     * @ORM\Column(name="theme_field", type="string", length=256, nullable=true)
     */
    private $theme;
    /**
     *
     * @ORM\Column(name="metal_field", type="string", length=256, nullable=true)
     */
    private $metal;
    /**
     *
     * @ORM\Column(name="insertion_type_field", type="string", length=256, nullable=true)
     */
    private $insertionType;
    /**
     *
     * @ORM\Column(name="poster_field", type="text", nullable=true)
     */
    private $poster;
    /**
     *
     * @ORM\Column(name="shortcut_field", type="text", nullable=true)
     */
    private $shortcut;
    /**
     *
     * @ORM\Column(name="state_field", type="string", length=256, nullable=true)
     */
    private $state;
    /**
     *
     * @ORM\Column(name="filter_price_field", type="string", length=30, nullable=true)
     */
    private $filterPrice;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ShareTag", mappedBy="sets")
     */
    private $shareTags;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OrderHasSet", mappedBy="set",indexBy="id")
     */
    private $orderHasSets;
    /**
     *
     * @ORM\Column(name="rating_field", type="integer", length=11, nullable=true)
     */
    private $rating;
    /**
     *
     * @ORM\Column(name="is_fresh_field", type="boolean", nullable=true)
     */
    private $isFresh = true;
    /**
     * @var \SetGallery
     *
     * @ORM\OneToMany(targetEntity="SetGallery", mappedBy="set",cascade={"persist","remove"}, orphanRemoval=true)
     *
     */
    private $setGallery;
    public function hasOldPrice(){
        if(count($this->getProducts())){
            foreach ($this->getProducts() as $item){
                if($item->getSharePrice()){
                    return true;
                }
            }
        }
        return false;
    }
    public function getOldPrice(){
        $price=0;
        if(count($this->getProducts())){
            foreach ($this->getProducts() as $item){
                $price+=$item->getPrice();
            }
        }
        return $price;
    }
    public function getShortClassName(){
        return "Set";
    }
    private $addSet=false;
    public function getAddSet(){
        return $this->addSet;
    }
    public function setAddSet($value){
        $this->addSet=$value;
        return $this;
    }
    public function __toString()
    {
        $string = "Набор";
        if($this->getCod()){
            $string.=" - ".$this->getCod();
        }
        return $string;
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Set
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return Set
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Set
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Set
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set cod
     *
     * @param string $cod
     *
     * @return Set
     */
    public function setCod($cod)
    {
        $this->cod = $cod;

        return $this;
    }

    /**
     * Get cod
     *
     * @return string
     */
    public function getCod()
    {
        return $this->cod;
    }

    /**
     * Add product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     *
     * @return Set
     */
    public function addProduct(\Site\BackendBundle\Entity\Product $product)
    {
        $product->addSet($this);
        $this->products[] = $product;
        return $this;
    }
    public function hasProduct(Product $product)
    {
        return $this->getProducts()->contains($product);
    }
    /**
     * Remove product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     */
    public function removeProduct(\Site\BackendBundle\Entity\Product $product)
    {
        $product->removeSet($this);
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set theme
     *
     * @param string $theme
     *
     * @return Set
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set metal
     *
     * @param string $metal
     *
     * @return Set
     */
    public function setMetal($metal)
    {
        $this->metal = $metal;

        return $this;
    }

    /**
     * Get metal
     *
     * @return string
     */
    public function getMetal()
    {
        return $this->metal;
    }

    /**
     * Set insertionType
     *
     * @param string $insertionType
     *
     * @return Set
     */
    public function setInsertionType($insertionType)
    {
        $this->insertionType = $insertionType;

        return $this;
    }

    /**
     * Get insertionType
     *
     * @return string
     */
    public function getInsertionType()
    {
        return $this->insertionType;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Set
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set poster
     *
     * @param string $poster
     *
     * @return Set
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get poster
     *
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set shortcut
     *
     * @param string $shortcut
     *
     * @return Set
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    /**
     * Get shortcut
     *
     * @return string
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }

    /**
     * Set filterPrice
     *
     * @param string $filterPrice
     *
     * @return Set
     */
    public function setFilterPrice($filterPrice)
    {
        $this->filterPrice = $filterPrice;

        return $this;
    }

    /**
     * Get filterPrice
     *
     * @return string
     */
    public function getFilterPrice()
    {
        return $this->filterPrice;
    }

    /**
     * Add shareTag
     *
     * @param \Site\BackendBundle\Entity\ShareTag $shareTag
     *
     * @return Set
     */
    public function addShareTag(\Site\BackendBundle\Entity\ShareTag $shareTag)
    {
        $shareTag->addSet($this);
        $this->shareTags[] = $shareTag;

        return $this;
    }

    /**
     * Remove shareTag
     *
     * @param \Site\BackendBundle\Entity\ShareTag $shareTag
     */
    public function removeShareTag(\Site\BackendBundle\Entity\ShareTag $shareTag)
    {
        $shareTag->removeSet($this);
        $this->shareTags->removeElement($shareTag);
    }

    /**
     * Get shareTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShareTags()
    {
        return $this->shareTags;
    }
    public function hasShareTag(ShareTag $tag)
    {
        return $this->getShareTags()->contains($tag);
    }
    public function hasDiscountTag(ShareTag $tag)
    {
//        $state = $this->getShareTags()->contains($tag);
        $state = false;
        if(count($products = $this->getProducts())){
            foreach ($products as $item){
                if($item->hasShareTag($tag)){
                    $state = true;
                    break;
                }
            }
        }
        return $state;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shareTags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orderHasSets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add orderHasSet
     *
     * @param \Site\BackendBundle\Entity\OrderHasSet $orderHasSet
     *
     * @return Set
     */
    public function addOrderHasSet(\Site\BackendBundle\Entity\OrderHasSet $orderHasSet)
    {
        $this->orderHasSets[] = $orderHasSet;

        return $this;
    }

    /**
     * Remove orderHasSet
     *
     * @param \Site\BackendBundle\Entity\OrderHasSet $orderHasSet
     */
    public function removeOrderHasSet(\Site\BackendBundle\Entity\OrderHasSet $orderHasSet)
    {
        $this->orderHasSets->removeElement($orderHasSet);
    }

    /**
     * Get orderHasSets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderHasSets()
    {
        return $this->orderHasSets;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Set
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set isFresh
     *
     * @param boolean $isFresh
     *
     * @return Set
     */
    public function setIsFresh($isFresh)
    {
        $this->isFresh = $isFresh;

        return $this;
    }

    /**
     * Get isFresh
     *
     * @return boolean
     */
    public function getIsFresh()
    {
        return $this->isFresh;
    }

    /**
     * Add setGallery
     *
     * @param \Site\BackendBundle\Entity\SetGallery $setGallery
     *
     * @return Set
     */
    public function addSetGallery(\Site\BackendBundle\Entity\SetGallery $setGallery)
    {
        $setGallery->setSet($this);
        $this->setGallery[] = $setGallery;

        return $this;
    }

    /**
     * Remove setGallery
     *
     * @param \Site\BackendBundle\Entity\SetGallery $setGallery
     */
    public function removeSetGallery(\Site\BackendBundle\Entity\SetGallery $setGallery)
    {
        $this->setGallery->removeElement($setGallery);
    }

    /**
     * Get setGallery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSetGallery()
    {
        return $this->setGallery;
    }
}
