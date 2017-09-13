<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Object
 *
 * @ORM\Table(name="object")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObjectRepository")
 */
class Object
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=10)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20)
     */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pronoun", type="string", length=10)
     */
    private $pronoun;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategoryObject", inversedBy="$objects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoryObject;


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
     * @return Object
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
     * Set value
     *
     * @param integer $value
     *
     * @return Object
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return Object
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Object
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set categoryObject
     *
     * @param \AppBundle\Entity\CategoryObject $categoryObject
     *
     * @return Object
     */
    public function setCategoryObject(\AppBundle\Entity\CategoryObject $categoryObject)
    {
        $this->categoryObject = $categoryObject;

        return $this;
    }

    /**
     * Get categoryObject
     *
     * @return \AppBundle\Entity\CategoryObject
     */
    public function getCategoryObject()
    {
        return $this->categoryObject;
    }

    /**
     * Set pronoun
     *
     * @param string $pronoun
     *
     * @return Object
     */
    public function setPronoun($pronoun)
    {
        $this->pronoun = $pronoun;

        return $this;
    }

    /**
     * Get pronoun
     *
     * @return string
     */
    public function getPronoun()
    {
        return $this->pronoun;
    }
}
