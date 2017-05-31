<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OrderHasSet
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="order_has_set_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\OrderHasSetRepository")
 */
class OrderHasSet
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OrderHasSetComponent", mappedBy="orderHasSet",cascade={"persist","remove"}, orphanRemoval=true,indexBy="id")
     */
    private $orderHasSetComponents;
    /**
     *
     * @var \Set
     *
     * @ORM\ManyToOne(targetEntity="Set",inversedBy="orderHasSets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="set_id", referencedColumnName="id")
     * })
     */
    private $set;
    /**
     *
     * @var \Order
     *
     * @ORM\ManyToOne(targetEntity="Order",inversedBy="orderHasSets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $order;
    /**
     * @ORM\Column(name="number", type="integer", length=11, nullable=true)
     */
    private $number=1;
    private $delete=false;
    public function getDelete(){
        return $this->delete;
    }
    public function setDelete($value){
        $this->delete=$value;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderHasSetComponents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return OrderHasSet
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
     * Add orderHasSetComponent
     *
     * @param \Site\BackendBundle\Entity\OrderHasSetComponent $orderHasSetComponent
     *
     * @return OrderHasSet
     */
    public function addOrderHasSetComponent(\Site\BackendBundle\Entity\OrderHasSetComponent $orderHasSetComponent)
    {
        $orderHasSetComponent->setOrderHasSet($this);
        $this->orderHasSetComponents[] = $orderHasSetComponent;

        return $this;
    }

    /**
     * Remove orderHasSetComponent
     *
     * @param \Site\BackendBundle\Entity\OrderHasSetComponent $orderHasSetComponent
     */
    public function removeOrderHasSetComponent(\Site\BackendBundle\Entity\OrderHasSetComponent $orderHasSetComponent)
    {
        $this->orderHasSetComponents->removeElement($orderHasSetComponent);
    }

    /**
     * Get orderHasSetComponents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderHasSetComponents()
    {
        return $this->orderHasSetComponents;
    }

    /**
     * Set set
     *
     * @param \Site\BackendBundle\Entity\Set $set
     *
     * @return OrderHasSet
     */
    public function setSet(\Site\BackendBundle\Entity\Set $set = null)
    {
        $this->set = $set;

        return $this;
    }

    /**
     * Get set
     *
     * @return \Site\BackendBundle\Entity\Set
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * Set order
     *
     * @param \Site\BackendBundle\Entity\Order $order
     *
     * @return OrderHasSet
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
}
