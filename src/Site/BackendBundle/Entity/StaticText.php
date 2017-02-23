<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * StaticText
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="static_text_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\StaticTextRepository")
 */
class StaticText
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $linkname;
    /**
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

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
     * @return StaticText
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
     * @return StaticText
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
}
