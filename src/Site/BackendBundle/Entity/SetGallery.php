<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Set Gallery
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="set_gallery_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\SetGalleyRepository")
 */
class SetGallery
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
     * @var \Set
     *
     * @ORM\ManyToOne(targetEntity="Set",inversedBy="setGallery")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="set_id", referencedColumnName="id")
     * })
     */
    private $set;

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
     * @return SetGallery
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
     * Set imageAlt
     *
     * @param string $imageAlt
     *
     * @return SetGallery
     */
    public function setImageAlt($imageAlt)
    {
        $this->imageAlt = $imageAlt;

        return $this;
    }

    /**
     * Get imageAlt
     *
     * @return string
     */
    public function getImageAlt()
    {
        return $this->imageAlt;
    }

    /**
     * Set set
     *
     * @param \Site\BackendBundle\Entity\Set $set
     *
     * @return SetGallery
     */
    public function setSet(\Site\BackendBundle\Entity\Set $set = null)
    {
        $this->set = $set;

        return $this;
    }

    /**
     * Get set
     *
     * @return \Site\BackendBundle\Entity\Set
     */
    public function getSet()
    {
        return $this->set;
    }
}
