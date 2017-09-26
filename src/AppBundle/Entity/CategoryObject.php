<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryObject
 *
 * @ORM\Table(name="category_object")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryObjectRepository")
 */
class CategoryObject
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="id_str", type="string", length=30)
     */
    private $idStr;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    
    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="consumable", type="boolean")
     */
    private $consumable;
    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Object", mappedBy="object")
     */
    private $objects;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CategoryObject
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add object
     *
     * @param \AppBundle\Entity\Object $object
     *
     * @return CategoryObject
     */
    public function addObject(\AppBundle\Entity\Object $object)
    {
        $this->objects[] = $object;

        return $this;
    }

    /**
     * Remove object
     *
     * @param \AppBundle\Entity\Object $object
     */
    public function removeObject(\AppBundle\Entity\Object $object)
    {
        $this->objects->removeElement($object);
    }

    /**
     * Get objects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjects()
    {
        return $this->objects;
    }


    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return CategoryObject
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set idStr
     *
     * @param string $idStr
     *
     * @return CategoryObject
     */
    public function setIdStr($idStr)
    {
        $this->idStr = $idStr;

        return $this;
    }

    /**
     * Get idStr
     *
     * @return string
     */
    public function getIdStr()
    {
        return $this->idStr;
    }

    

    /**
     * Set consumable
     *
     * @param boolean $consumable
     *
     * @return CategoryObject
     */
    public function setConsumable($consumable)
    {
        $this->consumable = $consumable;

        return $this;
    }

    /**
     * Get consumable
     *
     * @return boolean
     */
    public function getConsumable()
    {
        return $this->consumable;
    }
}
