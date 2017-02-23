<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Set
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product_set_table")
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
     * @ORM\Column(name="state_field", type="string", length=256, nullable=true)
     */
    private $state;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="InsertionColor", mappedBy="sets")
     */
    private $insertionColors;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Order", inversedBy="sets",cascade={"persist"})
     * @ORM\JoinTable(name="order_has_set")
     */
    private $orders;
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
     * Add insertionColor
     *
     * @param \Site\BackendBundle\Entity\InsertionColor $insertionColor
     *
     * @return Set
     */
    public function addInsertionColor(\Site\BackendBundle\Entity\InsertionColor $insertionColor)
    {
        $insertionColor->addSet($this);
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
        $insertionColor->removeSet($this);
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
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->insertionColors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add order
     *
     * @param \Site\BackendBundle\Entity\Order $order
     *
     * @return Set
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
}
