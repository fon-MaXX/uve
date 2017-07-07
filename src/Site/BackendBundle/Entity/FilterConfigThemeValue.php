<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterConfigThemeValue
 *
 * @ORM\Table(name="filter_config_theme_value", indexes={@ORM\Index(name="fk_filter_config_theme_value_filter_config_theme1_idx", columns={"filter_config_theme_id"})})
 * @ORM\Entity
 */
class FilterConfigThemeValue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var \FilterConfigTheme
     *
     * @ORM\ManyToOne(targetEntity="FilterConfigTheme")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="filter_config_theme_id", referencedColumnName="id")
     * })
     */
    private $filterConfigTheme;

    public function __toString()
    {
        return $this->getTitle();
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
     * @return FilterConfigThemeValue
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
     * Set filterConfigTheme
     *
     * @param \Site\BackendBundle\Entity\FilterConfigTheme $filterConfigTheme
     *
     * @return FilterConfigThemeValue
     */
    public function setFilterConfigTheme(\Site\BackendBundle\Entity\FilterConfigTheme $filterConfigTheme = null)
    {
        $this->filterConfigTheme = $filterConfigTheme;
    
        return $this;
    }

    /**
     * Get filterConfigTheme
     *
     * @return \Site\BackendBundle\Entity\FilterConfigTheme
     */
    public function getFilterConfigTheme()
    {
        return $this->filterConfigTheme;
    }
}
