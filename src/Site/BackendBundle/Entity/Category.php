<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Category
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product_category_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\CategoryRepository")
 */
class Category
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SubCategory", mappedBy="category",cascade={"persist","remove"}, orphanRemoval=true,indexBy="title")
     * @ORM\OrderBy({"position" = "DESC"})
     */
    private $subCategories;

    /**
     * @var string
     *
     * @ORM\Column(name="title_h1", type="string", length=255, nullable=false)
     */
    private $h1;

    function ucfirst_utf8($stri)
    {
        return ucfirst($stri);
    }

    public function __toString()
    {
        if ($this->getId()) {
            return $this->getTitle();
        }
        return "Новая категория";
    }

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

    /**
     * @return string
     */
    public function getTitleMeta()
    {
        return self::ucfirst_utf8($this->h1) . ' купить в интернет-магазине Ювелир Лайф';
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Add subCategory
     *
     * @param \Site\BackendBundle\Entity\SubCategory $subCategory
     *
     * @return Category
     */
    public function addSubCategory(\Site\BackendBundle\Entity\SubCategory $subCategory)
    {
        $this->subCategories[] = $subCategory;

        return $this;
    }

    /**
     * Remove subCategory
     *
     * @param \Site\BackendBundle\Entity\SubCategory $subCategory
     */
    public function removeSubCategory(\Site\BackendBundle\Entity\SubCategory $subCategory)
    {
        $this->subCategories->removeElement($subCategory);
    }

    /**
     * Get subCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubCategories()
    {
        return $this->subCategories;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subCategories = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
