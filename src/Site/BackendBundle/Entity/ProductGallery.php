<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product_gallery_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\ProductGalleyRepository")
 */
class ProductGallery
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
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;
    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageAlt;
    /**
     *
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product",inversedBy="productGallery")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @return mixed
     */
    public function getImageAlt()
    {
        return $this->imageAlt;
    }

    /**
     * @param mixed $imageAlt
     */
    public function setImageAlt($imageAlt)
    {
        $this->imageAlt = $imageAlt;
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
     * Set image
     *
     * @param string $image
     *
     * @return ProductGallery
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     *
     * @return ProductGallery
     */
    public function setProduct(\Site\BackendBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Site\BackendBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
