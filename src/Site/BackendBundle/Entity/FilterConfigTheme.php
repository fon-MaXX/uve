<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterConfigTheme
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="filter_config_theme", indexes={@ORM\Index(name="fk_filter_config_theme_filter_config_product_type1_idx", columns={"filter_config_product_type_id"})})
 * @ORM\Entity
 */
class FilterConfigTheme
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
     * @var \FilterConfigProductType
     *
     * @ORM\ManyToOne(targetEntity="FilterConfigProductType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="filter_config_product_type_id", referencedColumnName="id")
     * })
     */
    private $filterConfigProductType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FilterConfig", mappedBy="filterConfigTheme")
     */
    private $filterConfig;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="FilterConfigThemeValue", mappedBy="filterConfigTheme", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $filterConfigThemeValue;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filterConfig = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filterConfigThemeValue = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return ($this->filterConfigProductType) ? $this->filterConfigProductType->getTitle() : '';
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
     * Set filterConfigProductType
     *
     * @param \Site\BackendBundle\Entity\FilterConfigProductType $filterConfigProductType
     *
     * @return FilterConfigTheme
     */
    public function setFilterConfigProductType(\Site\BackendBundle\Entity\FilterConfigProductType $filterConfigProductType = null)
    {
        $this->filterConfigProductType = $filterConfigProductType;

        return $this;
    }

    /**
     * Get filterConfigProductType
     *
     * @return \Site\BackendBundle\Entity\FilterConfigProductType
     */
    public function getFilterConfigProductType()
    {
        return $this->filterConfigProductType;
    }

    /**
     * Add filterConfig
     *
     * @param \Site\BackendBundle\Entity\FilterConfig $filterConfig
     *
     * @return FilterConfigTheme
     */
    public function addFilterConfig(\Site\BackendBundle\Entity\FilterConfig $filterConfig)
    {
        $this->filterConfig[] = $filterConfig;

        return $this;
    }

    /**
     * Remove filterConfig
     *
     * @param \Site\BackendBundle\Entity\FilterConfig $filterConfig
     */
    public function removeFilterConfig(\Site\BackendBundle\Entity\FilterConfig $filterConfig)
    {
        $this->filterConfig->removeElement($filterConfig);
    }

    /**
     * Get filterConfig
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilterConfig()
    {
        return $this->filterConfig;
    }

    /**
     * Add filterConfigThemeValue
     *
     * @param \Site\BackendBundle\Entity\FilterConfigThemeValue $filterConfigThemeValue
     *
     * @return FilterConfigTheme
     */
    public function addFilterConfigThemeValue(\Site\BackendBundle\Entity\FilterConfigThemeValue $filterConfigThemeValue)
    {
        $filterConfigThemeValue->setFilterConfigTheme($this);
        $this->filterConfigThemeValue[] = $filterConfigThemeValue;

        return $this;
    }

    /**
     * Remove filterConfigThemeValue
     *
     * @param \Site\BackendBundle\Entity\FilterConfigThemeValue $filterConfigThemeValue
     */
    public function removeFilterConfigThemeValue(\Site\BackendBundle\Entity\FilterConfigThemeValue $filterConfigThemeValue)
    {
        $this->filterConfigThemeValue->removeElement($filterConfigThemeValue);
    }

    /**
     * Get filterConfigThemeValue
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilterConfigThemeValue()
    {
        return $this->filterConfigThemeValue;
    }
}
