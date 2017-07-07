<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterConfigState
 *
 * @ORM\Table(name="filter_config_state")
 * @ORM\Entity
 */
class FilterConfigState
{
    /* YES / NO */
    const YES = 1;
    const NO = 0;

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
     * @var integer
     *
     * @ORM\Column(name="in_admin_panel_only", type="integer", nullable=false)
     */
    private $inAdminPanelOnly;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FilterConfig", mappedBy="filterConfigState")
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
     * @return FilterConfigState
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
     * Set inAdminPanelOnly
     *
     * @param integer $inAdminPanelOnly
     *
     * @return FilterConfigState
     */
    public function setInAdminPanelOnly($inAdminPanelOnly)
    {
        $this->inAdminPanelOnly = $inAdminPanelOnly;

        return $this;
    }

    /**
     * Get inAdminPanelOnly
     *
     * @return integer
     */
    public function getInAdminPanelOnly()
    {
        return $this->inAdminPanelOnly;
    }

    /**
     * Add filterConfig
     *
     * @param \Site\BackendBundle\Entity\FilterConfig $filterConfig
     *
     * @return FilterConfigState
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

    public static function yesOrNoForm()
    {
        return [
            "Да" => self::YES,
            "Нет" => self::NO,
        ];
    }
}
