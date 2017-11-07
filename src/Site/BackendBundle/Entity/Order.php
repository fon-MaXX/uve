<?php

namespace Site\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Order
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="order_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\OrderRepository")
 */
class Order
{
    public $deliveryTypes=[
        'new-post'=>'Нова пошта',
        'ukr-post'=>'Укр пошта',
        'self-pickup'=>'Самовывоз (г. Житомир)'
    ];
    public $payTypes=[
        1=>'Карточкой',
        2=>'Наличными (при получении)',
    ];
    public $payTypesText=[
        1=>'После оформления заказа с Вами свяжется менеджер и предоставит реквизиты для оплаты',
        2=>'Оплата производится в момент получения товара на отделении почты. При этом ТК взимает дополнительную комиссию за перечисление денег',
    ];
    public $states = [
        'новый'=>'new',
        'выполнен'=>'accomplished',
        'отменен'=>'refused',
    ];
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
     *
     * @ORM\Column(name="comment_field", type="text", nullable=true)
     */
    private $comment;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OrderHasProduct", mappedBy="order",cascade={"persist","remove"}, orphanRemoval=true,indexBy="id")
     */
    private $orderHasProducts;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OrderHasSet", mappedBy="order",cascade={"persist","remove"}, orphanRemoval=true,indexBy="id")
     */
    private $orderHasSets;
    /**
     *
     * @ORM\Column(name="state_field", type="string", length=256, nullable=true)
     */
    private $state;
    /**
     *
     * @ORM\Column(name="pay_type_field", type="string", length=256, nullable=true)
     */
    private $payType;
    /**
     *
     * @ORM\Column(name="delivery_type_field", type="string", length=256, nullable=true)
     */
    private $deliveryType;
    /**
     *
     * @ORM\Column(name="price_field", type="integer", length=11, nullable=true)
     */
    private $price;
    /**
     *
     * @ORM\Column(name="discount_field", type="integer", length=11, nullable=true)
     */
    private $discount;
    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at",type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at",type="datetime")
     */
    private $updatedAt;
    /**
     *
     * @var \NovaPoshtaData
     *
     * @ORM\OneToOne(targetEntity="NovaPoshtaData", mappedBy="order", cascade={"persist","remove"}, orphanRemoval=true)
     *
     */
    private $novaPoshtaData;
    /**
     *
     * @var \UkrPoshtaData
     *
     * @ORM\OneToOne(targetEntity="UkrPoshtaData", mappedBy="order", cascade={"persist","remove"}, orphanRemoval=true)
     *
     */
    private $ukrPoshtaData;
    private $items;
    public function getItems(){
        $arr=[];
        if(count($this->getOrderHasProducts())){
            foreach($this->getOrderHasProducts() as $item){
                $arr[]=[
                    'cod'=>$item->getProduct()->getCod(),
                    'title'=>$item->getProduct()->getTitle()
                ];
            }
        }
        if(count($this->getOrderHasSets())){
            foreach($this->getOrderHasSets() as $item){
                $arr[]=[
                    'cod'=>$item->getSet()->getCod(),
                    'title'=>$item->getSet()->getTitle()
                ];
            }
        }
        return $arr;
    }
    public function setItems(){}
    public function __toString()
    {
        return ($this->id)?'Заказ №'.(1000000+$this->id):"Новый заказ";
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderHasProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orderHasSets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add orderHasProduct
     *
     * @param \Site\BackendBundle\Entity\OrderHasProduct $orderHasProduct
     *
     * @return Order
     */
    public function addOrderHasProduct(\Site\BackendBundle\Entity\OrderHasProduct $orderHasProduct)
    {
        $orderHasProduct->setOrder($this);
        $this->orderHasProducts[] = $orderHasProduct;

        return $this;
    }

    /**
     * Remove orderHasProduct
     *
     * @param \Site\BackendBundle\Entity\OrderHasProduct $orderHasProduct
     */
    public function removeOrderHasProduct(\Site\BackendBundle\Entity\OrderHasProduct $orderHasProduct)
    {
        $this->orderHasProducts->removeElement($orderHasProduct);
    }

    /**
     * Get orderHasProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderHasProducts()
    {
        return $this->orderHasProducts;
    }

    /**
     * Add orderHasSet
     *
     * @param \Site\BackendBundle\Entity\OrderHasSet $orderHasSet
     *
     * @return Order
     */
    public function addOrderHasSet(\Site\BackendBundle\Entity\OrderHasSet $orderHasSet)
    {
        $orderHasSet->setOrder($this);
        $this->orderHasSets[] = $orderHasSet;

        return $this;
    }

    /**
     * Remove orderHasSet
     *
     * @param \Site\BackendBundle\Entity\OrderHasSet $orderHasSet
     */
    public function removeOrderHasSet(\Site\BackendBundle\Entity\OrderHasSet $orderHasSet)
    {
        $this->orderHasSets->removeElement($orderHasSet);
    }

    /**
     * Get orderHasSets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderHasSets()
    {
        return $this->orderHasSets;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Order
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set payType
     *
     * @param string $payType
     *
     * @return Order
     */
    public function setPayType($payType)
    {
        $this->payType = $payType;

        return $this;
    }

    /**
     * Get payType
     *
     * @return string
     */
    public function getPayType()
    {
        return $this->payType;
    }

    /**
     * Set deliveryType
     *
     * @param string $deliveryType
     *
     * @return Order
     */
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    /**
     * Get deliveryType
     *
     * @return string
     */
    public function getDeliveryType()
    {
        return $this->deliveryType;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Order
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Order
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Order
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     *
     * @return Order
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set novaPoshtaData
     *
     * @param \Site\BackendBundle\Entity\NovaPoshtaData $novaPoshtaData
     *
     * @return Order
     */
    public function setNovaPoshtaData(\Site\BackendBundle\Entity\NovaPoshtaData $novaPoshtaData = null)
    {
        if ($novaPoshtaData) {
            $novaPoshtaData->setOrder($this);
        }
        $this->novaPoshtaData = $novaPoshtaData;

        return $this;
    }

    /**
     * Get novaPoshtaData
     *
     * @return \Site\BackendBundle\Entity\NovaPoshtaData
     */
    public function getNovaPoshtaData()
    {
        return $this->novaPoshtaData;
    }

    /**
     * Set ukrPoshtaData
     *
     * @param \Site\BackendBundle\Entity\UkrPoshtaData $ukrPoshtaData
     *
     * @return Order
     */
    public function setUkrPoshtaData(\Site\BackendBundle\Entity\UkrPoshtaData $ukrPoshtaData = null)
    {
        if ($ukrPoshtaData) {
            $ukrPoshtaData->setOrder($this);
        }
        $this->ukrPoshtaData = $ukrPoshtaData;

        return $this;
    }

    /**
     * Get ukrPoshtaData
     *
     * @return \Site\BackendBundle\Entity\UkrPoshtaData
     */
    public function getUkrPoshtaData()
    {
        return $this->ukrPoshtaData;
    }
}
