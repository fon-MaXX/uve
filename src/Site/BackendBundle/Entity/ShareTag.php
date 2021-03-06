<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ShareTag
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="share_tag_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\ShareTagRepository")
 */
class ShareTag
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
     * @ORM\Column(name="title", type="string",unique=true,length=255, nullable=true)
     */
    private $title;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="shareTags")
     * @ORM\JoinTable(name="product_has_share_tag",
     *   joinColumns={
     *     @ORM\JoinColumn(name="share_tag_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *   }
     * )
     */
    private $products;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Set", inversedBy="shareTags")
     * @ORM\JoinTable(name="set_has_share_tag",
     *   joinColumns={
     *     @ORM\JoinColumn(name="share_tag_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="set_id", referencedColumnName="id")
     *   }
     * )
     */
    private $sets;

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
     * @return ShareTag
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
     * Add product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     *
     * @return ShareTag
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
     * Add set
     *
     * @param \Site\BackendBundle\Entity\Set $set
     *
     * @return ShareTag
     */
    public function addSet(\Site\BackendBundle\Entity\Set $set)
    {
        $this->sets[] = $set;

        return $this;
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
}
