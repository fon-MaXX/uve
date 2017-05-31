<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OrderHasProduct
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="order_has_product_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\OrderHasProductRepository")
 */
class OrderHasProduct
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
     * @var \Order
     *
     * @ORM\ManyToOne(targetEntity="Order",inversedBy="orderHasProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_has_product_id", referencedColumnName="id")
     * })
     */
    private $order;
    /**
     *
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product",inversedBy="orderHasProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;
    /**
     * @ORM\Column(name="number", type="integer", length=11, nullable=true)
     */
    private $number=1;
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
    private $delete=false;
    public function getDelete(){
        return $this->delete;
    }
    public function setDelete($value){
        $this->delete=$value;
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
     * Set number
     *
     * @param integer $number
     *
     * @return OrderHasProduct
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set ringSize
     *
     * @param string $ringSize
     *
     * @return OrderHasProduct
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
     * @return OrderHasProduct
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
     * @return OrderHasProduct
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
     * Set order
     *
     * @param \Site\BackendBundle\Entity\Order $order
     *
     * @return OrderHasProduct
     */
    public function setOrder(\Site\BackendBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Site\BackendBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     *
     * @return OrderHasProduct
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
