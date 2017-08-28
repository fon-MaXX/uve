<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * StaticSeoPages
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="static_seo_pages_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\StaticSeoPagestRepository")
 */
class StaticSeoPages
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
     * @ORM\Column(name="linkname", type="string", length=255, nullable=false)
     */
    private $linkname;
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
     * @var string
     *
     * @ORM\Column(name="title_h1", type="string", length=255, nullable=false)
     */
    private $h1;

    public function __toString()
    {
        return ($this->getId())?$this->getTitle():'';
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set linkname
     *
     * @param string $linkname
     *
     * @return StaticSeoPages
     */
    public function setLinkname($linkname)
    {
        $this->linkname = $linkname;

        return $this;
    }

    /**
     * Get linkname
     *
     * @return string
     */
    public function getLinkname()
    {
        return $this->linkname;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return StaticSeoPages
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
     * @return StaticSeoPages
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
     * @return StaticSeoPages
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
}
