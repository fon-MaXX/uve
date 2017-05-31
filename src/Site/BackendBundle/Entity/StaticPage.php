<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * StaticPage
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="static_page_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\StaticPageRepository")
 */
class StaticPage
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="StaticPageContent", mappedBy="staticPage",cascade={"persist","remove"}, orphanRemoval=true, indexBy="linkname")
     * @ORM\OrderBy({"position" = "DESC"})
     */
    private $contents;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return StaticPage
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
     * Add content
     *
     * @param \Site\BackendBundle\Entity\StaticPageContent $content
     *
     * @return StaticPage
     */
    public function addContent(\Site\BackendBundle\Entity\StaticPageContent $content)
    {
        $this->contents[] = $content;

        return $this;
    }

    /**
     * Remove content
     *
     * @param \Site\BackendBundle\Entity\StaticPageContent $content
     */
    public function removeContent(\Site\BackendBundle\Entity\StaticPageContent $content)
    {
        $this->contents->removeElement($content);
    }

    /**
     * Get contents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContents()
    {
        return $this->contents;
    }
}
