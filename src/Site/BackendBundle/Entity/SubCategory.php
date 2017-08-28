<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SubCategory
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product_sub_category_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\SubCategoryRepository")
 */
class SubCategory
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
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
     * @var string
     *
     * @ORM\Column(name="title_meta", type="string", length=255, nullable=false)
     */
    private $titleMeta;

    /**
     * @Gedmo\Slug(fields={"title"},unique=true,separator="-")
     * @ORM\Column(name="slug", length=255, unique=true)
     */
    private $slug;
    /**
     *
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category",inversedBy="subCategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;
    /**
     * @ORM\Column(name="position_field", type="integer", length=11, nullable=true)
     */
    private $position;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="subCategory",cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $products;

    /**
     * @var string
     *
     * @ORM\Column(name="title_h1", type="string", length=255, nullable=false)
     */
    private $h1;

    /**
     * @return string
     */
    public function getH1()
    {
        return $this->h1;
    }

    /**
     * @param string $h1
     */
    public function setH1($h1)
    {
        $this->h1 = $h1;
    }

    function ucfirst_utf8($stri)
    {
        return ucfirst($stri);
    }

    public function __toString()
    {
        if ($this->getTitle()) {
            return $this->getTitle() . '(' . $this->getCategory()->getTitle() . ')';
        }
        return 'Новая подкатегория';
    }

    /**
     * @return string
     */
    public function getTitleMeta()
    {
        $pos = strripos($this->titleMeta, 'купить в интернет-магазине Ювелир Лайф');
        if ($pos === false) {
            return self::ucfirst_utf8($this->h1) . ' купить в интернет-магазине Ювелир Лайф';
        } else {
            return self::ucfirst_utf8($this->titleMeta);
        }

    }

    /**
     * @param string $titleMeta
     */
    public function setTitleMeta($titleMeta)
    {
        $this->titleMeta = $titleMeta;
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
     * @return SubCategory
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
     * @return SubCategory
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
        return self::ucfirst_utf8($this->h1);
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return SubCategory
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
        return 'Хотите купить ' . mb_strtolower ($this->h1) . ' в интернет-магазине? Заходите в Ювелир Лайф: ✓Низкие цены ✓Доставка по Киеву и Украине!';
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return SubCategory
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
     * Set category
     *
     * @param \Site\BackendBundle\Entity\Category $category
     *
     * @return SubCategory
     */
    public function setCategory(\Site\BackendBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Site\BackendBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     *
     * @return SubCategory
     */
    public function addProduct(\Site\BackendBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     */
    public function removeProduct(\Site\BackendBundle\Entity\Product $product)
    {
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
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set position
     *
     * @param integer $position
     *
     * @return SubCategory
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
}
