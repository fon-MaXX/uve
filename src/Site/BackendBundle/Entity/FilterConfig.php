<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterConfig
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="filter_config")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\FilterConfigRepository")
 */
class FilterConfig
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
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FilterConfigChainSizes", inversedBy="filterConfig",cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="filter_config_has_filter_config_chain_sizes",
     *   joinColumns={
     *     @ORM\JoinColumn(name="filter_config_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="filter_config_chain_sizes_id", referencedColumnName="id")
     *   }
     * )
     */
    private $filterConfigChainSizes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FilterConfigInsertionColors", inversedBy="filterConfig",cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="filter_config_has_filter_config_insertion_colors",
     *   joinColumns={
     *     @ORM\JoinColumn(name="filter_config_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="filter_config_insertion_colors_id", referencedColumnName="id")
     *   }
     * )
     */
    private $filterConfigInsertionColors;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FilterConfigInsertionType", inversedBy="filterConfig",cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="filter_config_has_filter_config_insertion_type",
     *   joinColumns={
     *     @ORM\JoinColumn(name="filter_config_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="filter_config_insertion_type_id", referencedColumnName="id")
     *   }
     * )
     */
    private $filterConfigInsertionType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FilterConfigState", inversedBy="filterConfig",cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="filter_config_has_filter_config_state",
     *   joinColumns={
     *     @ORM\JoinColumn(name="filter_config_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="filter_config_state_id", referencedColumnName="id")
     *   }
     * )
     */
    private $filterConfigState;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FilterConfigTheme", inversedBy="filterConfig",cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="filter_config_has_filter_config_theme",
     *   joinColumns={
     *     @ORM\JoinColumn(name="filter_config_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="filter_config_theme_id", referencedColumnName="id")
     *   }
     * )
     */
    private $filterConfigTheme;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filterConfigChainSizes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filterConfigInsertionColors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filterConfigInsertionType = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filterConfigState = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filterConfigTheme = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return 'Настройки фильтров';
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
     * Set price
     *
     * @param integer $price
     *
     * @return FilterConfig
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPriceForFilter()
    {
        return $this->price;
    }

    /**
     * Add filterConfigChainSize
     *
     * @param \Site\BackendBundle\Entity\FilterConfigChainSizes $filterConfigChainSize
     *
     * @return FilterConfig
     */
    public function addFilterConfigChainSize(\Site\BackendBundle\Entity\FilterConfigChainSizes $filterConfigChainSize)
    {
        $this->filterConfigChainSizes[] = $filterConfigChainSize;

        return $this;
    }

    /**
     * Remove filterConfigChainSize
     *
     * @param \Site\BackendBundle\Entity\FilterConfigChainSizes $filterConfigChainSize
     */
    public function removeFilterConfigChainSize(\Site\BackendBundle\Entity\FilterConfigChainSizes $filterConfigChainSize)
    {
        $this->filterConfigChainSizes->removeElement($filterConfigChainSize);
    }

    public function getFilterConfigChainSizesForFilter()
    {
        $result = [];

        foreach ($this->filterConfigChainSizes as $filterConfigChainSizes) {
            $result[] = $filterConfigChainSizes->getTitle();
        }

        return $result;
    }

    /**
     * Add filterConfigInsertionColor
     *
     * @param \Site\BackendBundle\Entity\FilterConfigInsertionColors $filterConfigInsertionColor
     *
     * @return FilterConfig
     */
    public function addFilterConfigInsertionColor(\Site\BackendBundle\Entity\FilterConfigInsertionColors $filterConfigInsertionColor)
    {
        $this->filterConfigInsertionColors[] = $filterConfigInsertionColor;

        return $this;
    }

    /**
     * Remove filterConfigInsertionColor
     *
     * @param \Site\BackendBundle\Entity\FilterConfigInsertionColors $filterConfigInsertionColor
     */
    public function removeFilterConfigInsertionColor(\Site\BackendBundle\Entity\FilterConfigInsertionColors $filterConfigInsertionColor)
    {
        $this->filterConfigInsertionColors->removeElement($filterConfigInsertionColor);
    }

    /**
     * Get filterConfigInsertionColors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilterConfigInsertionColors()
    {
        return $this->filterConfigInsertionColors;
    }

    /**
     * Add filterConfigInsertionType
     *
     * @param \Site\BackendBundle\Entity\FilterConfigInsertionType $filterConfigInsertionType
     *
     * @return FilterConfig
     */
    public function addFilterConfigInsertionType(\Site\BackendBundle\Entity\FilterConfigInsertionType $filterConfigInsertionType)
    {
        $this->filterConfigInsertionType[] = $filterConfigInsertionType;

        return $this;
    }

    /**
     * Remove filterConfigInsertionType
     *
     * @param \Site\BackendBundle\Entity\FilterConfigInsertionType $filterConfigInsertionType
     */
    public function removeFilterConfigInsertionType(\Site\BackendBundle\Entity\FilterConfigInsertionType $filterConfigInsertionType)
    {
        $this->filterConfigInsertionType->removeElement($filterConfigInsertionType);
    }

    public function getFilterConfigInsertionTypeForFilter()
    {
        $result = [];

        foreach ($this->filterConfigInsertionType as $filterConfigInsertionType) {
            $result[] = $filterConfigInsertionType->getTitle();
        }

        return $result;
    }

    /**
     * Add filterConfigState
     *
     * @param \Site\BackendBundle\Entity\FilterConfigState $filterConfigState
     *
     * @return FilterConfig
     */
    public function addFilterConfigState(\Site\BackendBundle\Entity\FilterConfigState $filterConfigState)
    {
        $this->filterConfigState[] = $filterConfigState;

        return $this;
    }

    /**
     * Remove filterConfigState
     *
     * @param \Site\BackendBundle\Entity\FilterConfigState $filterConfigState
     */
    public function removeFilterConfigState(\Site\BackendBundle\Entity\FilterConfigState $filterConfigState)
    {
        $this->filterConfigState->removeElement($filterConfigState);
    }

    public function getFilterConfigStateForFilter()
    {
        $result = [];
        foreach ($this->filterConfigState as $filterConfigState) {
            if ($filterConfigState->getInAdminPanelOnly() == FilterConfigState::NO) {
                $result[] = $filterConfigState->getTitle();
            }

        }

        return $result;
    }

    /**
     * Add filterConfigTheme
     *
     * @param \Site\BackendBundle\Entity\FilterConfigTheme $filterConfigTheme
     *
     * @return FilterConfig
     */
    public function addFilterConfigTheme(\Site\BackendBundle\Entity\FilterConfigTheme $filterConfigTheme)
    {
        $this->filterConfigTheme[] = $filterConfigTheme;

        return $this;
    }

    /**
     * Remove filterConfigTheme
     *
     * @param \Site\BackendBundle\Entity\FilterConfigTheme $filterConfigTheme
     */
    public function removeFilterConfigTheme(\Site\BackendBundle\Entity\FilterConfigTheme $filterConfigTheme)
    {
        $this->filterConfigTheme->removeElement($filterConfigTheme);
    }

    public function getFilterConfigThemeForFilter()
    {
        $result = [];
        foreach ($this->filterConfigTheme as $filterConfigTheme) {
            if ($filterConfigTheme->getFilterConfigProductType()) {
                $result[$filterConfigTheme->getFilterConfigProductType()->getTitle()] = [];
                foreach ($filterConfigTheme->getFilterConfigThemeValue() as $filterConfigThemeValue) {
                    $result[$filterConfigTheme->getFilterConfigProductType()->getTitle()][]
                        = $filterConfigThemeValue->getTitle();
                }
            }
        }

        return $result;
    }

    private $filters = [
        'наборы' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter'
        ],
        'кольца' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter'
        ],
        'серьги' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter'
        ],
        'подвесы' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter'
        ],
        'аксессуары' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter'
        ],
        'пирсинг' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter'
        ],
        'цепи и браслеты' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter',
            'getFilterConfigChainSizesForFilter'
        ],
        'пандора' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter'
        ],
        'золото' => [
            'getPriceForFilter', 'getFilterConfigThemeForFilter',
            'getFilterConfigStateForFilter', 'getFilterConfigInsertionTypeForFilter'
        ],
    ];

    public function getFilterConfig($title)
    {
        if (!$title || (!isset($this->filters[$title])) || (!count($this->filters[$title]))) {
            return false;
        }
        $result = [];

        foreach ($this->filters[$title] as $value) {
            $item = $this->$value();
            if ($value == 'getPriceForFilter') {
                $value = 'price';
            } elseif ($value == 'getFilterConfigThemeForFilter') {
                $value = 'theme';
            } elseif ($value == 'getFilterConfigStateForFilter') {
                $value = 'state';
            } elseif ($value == 'getFilterConfigInsertionTypeForFilter') {
                $value = 'insertionType';
            } elseif ($value == 'getFilterConfigChainSizesForFilter') {
                $value = 'chainSizes';
            }

            if ($value == 'theme') {
                (count($item[$title])) ? $result[$value] = $item[$title] : '';
            } else {
                $result[$value] = $item;
            }
        }

        return $result;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get filterConfigChainSizes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilterConfigChainSizes()
    {
        return $this->filterConfigChainSizes;
    }

    /**
     * Get filterConfigInsertionType
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilterConfigInsertionType()
    {
        return $this->filterConfigInsertionType;
    }

    /**
     * Get filterConfigState
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilterConfigState()
    {
        return $this->filterConfigState;
    }

    /**
     * Get filterConfigTheme
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilterConfigTheme()
    {
        return $this->filterConfigTheme;
    }
}
