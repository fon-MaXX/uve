<?php
namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * UkrPoshtaData
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="ukr_poshta_data_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\UkrPoshtaDataRepository")
 */
class UkrPoshtaData
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    /**
     *
     * @ORM\Column(name="address_field", type="text", nullable=true)
     */
    protected $address;
    /**
     * @var \Order
     *
     * @ORM\OneToOne(targetEntity="Order", inversedBy="ukrPoshtaData")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

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
     * Set address
     *
     * @param string $address
     *
     * @return UkrPoshtaData
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set order
     *
     * @param \Site\BackendBundle\Entity\Order $order
     *
     * @return UkrPoshtaData
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
