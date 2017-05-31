<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * StaticPageContent
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="static_page_content_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\StaticPageContentRepository")
 */
class StaticPageContent
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
     * @ORM\Column(name="text_field", type="text", nullable=true)
     */
    private $text;
    /**
     *
     * @var \StaticPage
     *
     * @ORM\ManyToOne(targetEntity="StaticPage",inversedBy="contents")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="static_page_id", referencedColumnName="id")
     * })
     */
    private $staticPage;
    public function __toString()
    {
        return ($this->id)?$this->getStaticPage()->getTitle().' >> '.$this->getLinkname():"";
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
     * @return StaticPageContent
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
     * Set text
     *
     * @param string $text
     *
     * @return StaticPageContent
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set staticPage
     *
     * @param \Site\BackendBundle\Entity\StaticPage $staticPage
     *
     * @return StaticPageContent
     */
    public function setStaticPage(\Site\BackendBundle\Entity\StaticPage $staticPage = null)
    {
        $this->staticPage = $staticPage;

        return $this;
    }

    /**
     * Get staticPage
     *
     * @return \Site\BackendBundle\Entity\StaticPage
     */
    public function getStaticPage()
    {
        return $this->staticPage;
    }
}
