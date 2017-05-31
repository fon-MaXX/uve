<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OrderHasSetComponent
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="order_has_set_component_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\OrderHasSetComponentRepository")
 */
class OrderHasSetComponent
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
     * @var \OrderHasSet
     *
     * @ORM\ManyToOne(targetEntity="OrderHasSet",inversedBy="orderHasSetComponents")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_has_set_id", referencedColumnName="id")
     * })
     */
    private $orderHasSet;
    /**
     *
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product",inversedBy="orderHasSetComponents")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;
    /**
     * @ORM\Column(name="presence", type="boolean", nullable=true)
     */
    private $presence=true;
    /**
     * @ORM\Column(name="ring_size", type="string", length=255, nullable=true)
     */
    private $ringSize;
    /**
     * @ORM\Column(name="chain_size", type="string", length=255, nullable=true)
     */
    private $chainSize;
    /**
     * @ORM\Column(name="insertion_color", type="string", length=255, nullable=true)
     */
    private $insertionColor;

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
     * Set presence
     *
     * @param boolean $presence
     *
     * @return OrderHasSetComponent
     */
    public function setPresence($presence)
    {
        $this->presence = $presence;

        return $this;
    }

    /**
     * Get presence
     *
     * @return boolean
     */
    public function getPresence()
    {
        return $this->presence;
    }

    /**
     * Set ringSize
     *
     * @param string $ringSize
     *
     * @return OrderHasSetComponent
     */
    public function setRingSize($ringSize)
    {
        $this->ringSize = $ringSize;

        return $this;
    }

    /**
     * Get ringSize
     *
     * @return string
     */
    public function getRingSize()
    {
        return $this->ringSize;
    }

    /**
     * Set chainSize
     *
     * @param string $chainSize
     *
     * @return OrderHasSetComponent
     */
    public function setChainSize($chainSize)
    {
        $this->chainSize = $chainSize;

        return $this;
    }

    /**
     * Get chainSize
     *
     * @return string
     */
    public function getChainSize()
    {
        return $this->chainSize;
    }

    /**
     * Set insertionColor
     *
     * @param string $insertionColor
     *
     * @return OrderHasSetComponent
     */
    public function setInsertionColor($insertionColor)
    {
        $this->insertionColor = $insertionColor;

        return $this;
    }

    /**
     * Get insertionColor
     *
     * @return string
     */
    public function getInsertionColor()
    {
        return $this->insertionColor;
    }

    /**
     * Set orderHasSet
     *
     * @param \Site\BackendBundle\Entity\OrderHasSet $orderHasSet
     *
     * @return OrderHasSetComponent
     */
    public function setOrderHasSet(\Site\BackendBundle\Entity\OrderHasSet $orderHasSet = null)
    {
        $this->orderHasSet = $orderHasSet;

        return $this;
    }

    /**
     * Get orderHasSet
     *
     * @return \Site\BackendBundle\Entity\OrderHasSet
     */
    public function getOrderHasSet()
    {
        return $this->orderHasSet;
    }

    /**
     * Set product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     *
     * @return OrderHasSetComponent
     */
    public function setProduct(\Site\BackendBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Site\BackendBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
