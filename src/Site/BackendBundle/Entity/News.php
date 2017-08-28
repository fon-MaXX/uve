<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * News
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="news_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\NewsRepository")
 */
class News
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
     * @ORM\Column(name="shortcut", type="string", length=500, nullable=true)
     */
    private $shortcut;
    /**
     *
     * @ORM\Column(name="poster", type="text", nullable=true)
     */
    private $poster;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $posterAlt;

    /**
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="NewsTag", mappedBy="articles")
     */
    private $newsTags;
    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at",type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at",type="datetime")
     */
    private $updatedAt;
    public function getShortClassName(){
        return "News";
    }
    public function __toString()
    {
        return 'Новость:"'.$this->getTitle().'""';
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->newsTags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function ucfirst_utf8($stri){
       return ucfirst($stri);
    }

    /**
     * @return mixed
     */
    public function getTitleMeta()
    {
        $pos = strripos($this->titleMeta, ' - Ювелир Лайф');
        if ($pos === false) {
            return self::ucfirst_utf8($this->title) . ' - Ювелир Лайф';
        } else {
            return $this->titleMeta;
        }
    }

    /**
     * @param mixed $titleMeta
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
     * @return News
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
     * @return News
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
        if (is_null($this->keywords)) {
            return self::ucfirst_utf8($this->title);
        } else {
            return $this->keywords;
        }
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return News
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
        if (is_null($this->description)) {
            return 'Читайте новость "' . $this->title . '" в блоге интернет-магазина Ювелир Лайф.';
        } else {
            return $this->description;
        }
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return News
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
     * Set shortcut
     *
     * @param string $shortcut
     *
     * @return News
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    /**
     * Get shortcut
     *
     * @return string
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }

    /**
     * Set poster
     *
     * @param string $poster
     *
     * @return News
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get poster
     *
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set posterAlt
     *
     * @param string $posterAlt
     *
     * @return News
     */
    public function setPosterAlt($posterAlt)
    {
        $this->posterAlt = $posterAlt;

        return $this;
    }

    /**
     * Get posterAlt
     *
     * @return string
     */
    public function getPosterAlt()
    {
        return $this->posterAlt;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return News
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
     * Set createdAt
     *
     * @param string $createdAt
     *
     * @return News
     */
    public function setCreatedAt($createdAt)
    {

        $this->createdAt= \DateTime::createFromFormat('d-m-Y', $createdAt);
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        if (!$this->createdAt) return $this->createdAt;
        return $this->createdAt->format( "d-m-Y" );
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return News
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add newsTag
     *
     * @param \Site\BackendBundle\Entity\NewsTag $newsTag
     *
     * @return News
     */
    public function addNewsTag(\Site\BackendBundle\Entity\NewsTag $newsTag)
    {
        $newsTag->addArticle($this);
        $this->newsTags[] = $newsTag;

        return $this;
    }

    /**
     * Remove newsTag
     *
     * @param \Site\BackendBundle\Entity\NewsTag $newsTag
     */
    public function removeNewsTag(\Site\BackendBundle\Entity\NewsTag $newsTag)
    {
        $this->newsTags->removeElement($newsTag);
    }

    /**
     * Get newsTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNewsTags()
    {
        return $this->newsTags;
    }
}
