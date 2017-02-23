<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Order
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="order_set_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\OrderRepository")
 */
class Order
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
     * @ORM\Column(name="username_field", type="string", length=500, nullable=true)
     */
    private $username;
    /**
     *
     * @ORM\Column(name="email_field", type="string", length=500, nullable=true)
     */
    private $email;
    /**
     *
     * @ORM\Column(name="phone_field", type="string", length=500, nullable=true)
     */
    private $phone;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="orders",cascade={"persist"})
     */
    private $products;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Set", mappedBy="orders",cascade={"persist"})
     */
    private $sets;
    /**
     *
     * @ORM\Column(name="state_field", type="string", length=256, nullable=true)
     */
    private $state;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return Order
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Order
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Order
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     *
     * @return Order
     */
    public function addProduct(\Site\BackendBundle\Entity\Product $product)
    {
        $product->addOrder($this);
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Site\BackendBundle\Entity\Product $product
     */
    public function removeProduct(\Site\BackendBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add set
     *
     * @param \Site\BackendBundle\Entity\Set $set
     *
     * @return Order
     */
    public function addSet(\Site\BackendBundle\Entity\Set $set)
    {
        $set->addOrder($this);
        $this->sets[] = $set;

        return $this;
    }

    /**
     * Remove set
     *
     * @param \Site\BackendBundle\Entity\Set $set
     */
    public function removeSet(\Site\BackendBundle\Entity\Set $set)
    {
        $this->sets->removeElement($set);
    }

    /**
     * Get sets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSets()
    {
        return $this->sets;
    }
}
