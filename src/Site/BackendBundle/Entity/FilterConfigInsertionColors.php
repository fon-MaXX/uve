<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterConfigInsertionColors
 *
 * @ORM\Table(name="filter_config_insertion_colors")
 * @ORM\Entity
 */
class FilterConfigInsertionColors
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FilterConfig", mappedBy="filterConfigInsertionColors")
     */
    private $filterConfig;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filterConfig = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return FilterConfigInsertionColors
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
     * Add filterConfig
     *
     * @param \Site\BackendBundle\Entity\FilterConfig $filterConfig
     *
     * @return FilterConfigInsertionColors
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
}
