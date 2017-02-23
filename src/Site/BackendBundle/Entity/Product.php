<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\ProductRepository")
 */
class Product
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
     *
     * @ORM\Column(name="cod_field", type="string", length=255, nullable=false, unique=true)
     */
    private $cod;
    /**
     *
     * @ORM\Column(name="shortcut_field", type="text", nullable=true)
     */
    private $shortcut;
    /**
     *
     * @ORM\Column(name="weight_field", type="string", length=256, nullable=true)
     */
    private $weight;
    /**
     *
     * @ORM\Column(name="price_field", type="string", length=30, nullable=true)
     */
    private $price;
    /**
     *
     * @ORM\Column(name="share_price_field", type="string", length=30, nullable=true)
     */
    private $sharePrice;
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
     * @ORM\Column(name="insertion_shape_field", type="string", length=256, nullable=true)
     */
    private $insertionShape;
    /**
     *
     * @ORM\Column(name="insertion_parameters_field", type="string", length=256, nullable=true)
     */
    private $insertionParameters;
    /**
     *
     * @ORM\Column(name="product_parameters_field", type="string", length=256, nullable=true)
     */
    private $productParameters;
    /**
     *
     * @ORM\Column(name="weaving_type_field", type="string", length=256, nullable=true)
     */
    private $weavingType;
    /**
     *
     * @ORM\Column(name="chain_length_field", type="string", length=256, nullable=true)
     */
    private $chainLength;
    /**
     *
     * @ORM\Column(name="covering_field", type="string", length=256, nullable=true)
     */
    private $covering;
    /**
     *
     * @ORM\Column(name="product_type_field", type="string", length=256, nullable=true)
     */
    private $productType;
    /**
     *
     * @ORM\Column(name="theme_field", type="string", length=256, nullable=true)
     */
    private $theme;
    /**
     *
     * @ORM\Column(name="poster_field", type="text", nullable=true)
     */
    private $poster;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Set", inversedBy="products",cascade={"persist"})
     * @ORM\JoinTable(name="set_has_product")
     */
    private $sets;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Order", inversedBy="products",cascade={"persist"})
     * @ORM\JoinTable(name="order_has_product")
     */
    private $orders;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ShareTag", mappedBy="products")
     */
    private $shareTags;
    /**
     *
     * @ORM\Column(name="state_field", type="string", length=256, nullable=true)
     */
    private $state;
    /**
     *
     * @ORM\Column(name="manufacturer_field", type="string", length=256, nullable=true)
     */
    private $manufacturer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="RingSize", mappedBy="products")
     */
    private $ringSizes;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="InsertionColor", mappedBy="products")
     */
    private $insertionColors;
    /**
     *
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="SubCategory",inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sub_category_id", referencedColumnName="id")
     * })
     */
    private $subCategory;
    public function getTagTitle(){
        return $this->getCod().' - '.$this->getTitle();
    }
    public function __toString()
    {
        $string = "Товар";
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
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * Set shortcut
     *
     * @param string $shortcut
     *
     * @return Product
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
     * Set weight
     *
     * @param string $weight
     *
     * @return Product
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set sharePrice
     *
     * @param string $sharePrice
     *
     * @return Product
     */
    public function setSharePrice($sharePrice)
    {
        $this->sharePrice = $sharePrice;

        return $this;
    }

    /**
     * Get sharePrice
     *
     * @return string
     */
    public function getSharePrice()
    {
        return $this->sharePrice;
    }

    /**
     * Set metal
     *
     * @param string $metal
     *
     * @return Product
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
     * @return Product
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
     * Set insertionShape
     *
     * @param string $insertionShape
     *
     * @return Product
     */
    public function setInsertionShape($insertionShape)
    {
        $this->insertionShape = $insertionShape;

        return $this;
    }

    /**
     * Get insertionShape
     *
     * @return string
     */
    public function getInsertionShape()
    {
        return $this->insertionShape;
    }

    /**
     * Set insertionParameters
     *
     * @param string $insertionParameters
     *
     * @return Product
     */
    public function setInsertionParameters($insertionParameters)
    {
        $this->insertionParameters = $insertionParameters;

        return $this;
    }

    /**
     * Get insertionParameters
     *
     * @return string
     */
    public function getInsertionParameters()
    {
        return $this->insertionParameters;
    }

    /**
     * Set productParameters
     *
     * @param string $productParameters
     *
     * @return Product
     */
    public function setProductParameters($productParameters)
    {
        $this->productParameters = $productParameters;

        return $this;
    }

    /**
     * Get productParameters
     *
     * @return string
     */
    public function getProductParameters()
    {
        return $this->productParameters;
    }

    /**
     * Set weavingType
     *
     * @param string $weavingType
     *
     * @return Product
     */
    public function setWeavingType($weavingType)
    {
        $this->weavingType = $weavingType;

        return $this;
    }

    /**
     * Get weavingType
     *
     * @return string
     */
    public function getWeavingType()
    {
        return $this->weavingType;
    }

    /**
     * Set chainLength
     *
     * @param string $chainLength
     *
     * @return Product
     */
    public function setChainLength($chainLength)
    {
        $this->chainLength = $chainLength;

        return $this;
    }

    /**
     * Get chainLength
     *
     * @return string
     */
    public function getChainLength()
    {
        return $this->chainLength;
    }

    /**
     * Set covering
     *
     * @param string $covering
     *
     * @return Product
     */
    public function setCovering($covering)
    {
        $this->covering = $covering;

        return $this;
    }

    /**
     * Get covering
     *
     * @return string
     */
    public function getCovering()
    {
        return $this->covering;
    }

    /**
     * Set productType
     *
     * @param string $productType
     *
     * @return Product
     */
    public function setProductType($productType)
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * Get productType
     *
     * @return string
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * Set theme
     *
     * @param string $theme
     *
     * @return Product
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
     * Add ringSize
     *
     * @param \Site\BackendBundle\Entity\RingSize $ringSize
     *
     * @return Product
     */
    public function addRingSize(\Site\BackendBundle\Entity\RingSize $ringSize)
    {
        $ringSize->addProduct($this);
        $this->ringSizes[] = $ringSize;

        return $this;
    }

    /**
     * Remove ringSize
     *
     * @param \Site\BackendBundle\Entity\RingSize $ringSize
     */
    public function removeRingSize(\Site\BackendBundle\Entity\RingSize $ringSize)
    {
        $ringSize->removeProduct($this);
        $this->ringSizes->removeElement($ringSize);
    }

    /**
     * Get ringSizes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRingSizes()
    {
        return $this->ringSizes;
    }
    public function hasRingSize(RingSize $size)
    {
        return $this->getRingSizes()->contains($size);
    }
    /**
     * Add insertionColor
     *
     * @param \Site\BackendBundle\Entity\InsertionColor $insertionColor
     *
     * @return Product
     */
    public function addInsertionColor(\Site\BackendBundle\Entity\InsertionColor $insertionColor)
    {
        $insertionColor->addProduct($this);
        $this->insertionColors[] = $insertionColor;

        return $this;
    }
    public function hasInsertionColor(InsertionColor $color)
    {
        return $this->getInsertionColors()->contains($color);
    }

    /**
     * Remove insertionColor
     *
     * @param \Site\BackendBundle\Entity\InsertionColor $insertionColor
     */
    public function removeInsertionColor(\Site\BackendBundle\Entity\InsertionColor $insertionColor)
    {
        $insertionColor->removeProduct($this);
        $this->insertionColors->removeElement($insertionColor);
    }

    /**
     * Get insertionColors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInsertionColors()
    {
        return $this->insertionColors;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Product
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
     * Set manufacturer
     *
     * @param string $manufacturer
     *
     * @return Product
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer
     *
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Add set
     *
     * @param \Site\BackendBundle\Entity\Set $set
     *
     * @return Product
     */
    public function addSet(\Site\BackendBundle\Entity\Set $set)
    {
        $this->sets[] = $set;

        return $this;
    }
    public function hasSet(Set $set)
    {
        return $this->getSets()->contains($set);
    }

    /**
     * Remove set
     *
     * @param \Site\BackendBundle\Entity\Set $set
     */
    public function removeSet(\Site\BackendBundle\Entity\Set $set)
    {
        $this->sets->removeElement($set);
    }

    /**
     * Get sets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * Add shareTag
     *
     * @param \Site\BackendBundle\Entity\ShareTag $shareTag
     *
     * @return Product
     */
    public function addShareTag(\Site\BackendBundle\Entity\ShareTag $shareTag)
    {
        $shareTag->addProduct($this);
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
    /**
     * Set subCategory
     *
     * @param \Site\BackendBundle\Entity\SubCategory $subCategory
     *
     * @return Product
     */
    public function setSubCategory(\Site\BackendBundle\Entity\SubCategory $subCategory = null)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * Get subCategory
     *
     * @return \Site\BackendBundle\Entity\SubCategory
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shareTags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ringSizes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->insertionColors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add order
     *
     * @param \Site\BackendBundle\Entity\Order $order
     *
     * @return Product
     */
    public function addOrder(\Site\BackendBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \Site\BackendBundle\Entity\Order $order
     */
    public function removeOrder(\Site\BackendBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set poster
     *
     * @param string $poster
     *
     * @return Product
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
}
