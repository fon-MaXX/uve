<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/13/2016
 * Time: 04:44 PM
 */
namespace Site\BackendBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * NovaPoshtaRegion
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="nova_poshta_region_table")
 * @ORM\Entity(repositoryClass="Site\BackendBundle\Entity\Repository\NovaPoshtaRegionRepository")
 */
class NovaPoshtaRegion{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="text", nullable=false)
     */
    private $ref;
    /**
     * @var string
     *
     * @ORM\Column(name="areas_center", type="text", nullable=false)
     */
    private $areasCenter;

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
     * Set description
     *
     * @param string $description
     *
     * @return NovaPoshtaRegion
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set ref
     *
     * @param string $ref
     *
     * @return NovaPoshtaRegion
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set areasCenter
     *
     * @param string $areasCenter
     *
     * @return NovaPoshtaRegion
     */
    public function setAreasCenter($areasCenter)
    {
        $this->areasCenter = $areasCenter;

        return $this;
    }

    /**
     * Get areasCenter
     *
     * @return string
     */
    public function getAreasCenter()
    {
        return $this->areasCenter;
    }
}
