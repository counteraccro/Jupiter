<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
 * @UniqueEntity("name")
 */
class Player {
	/**
	 *
	 * @var int @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string @Assert\NotBlank()
	 *      @ORM\Column(name="name", type="string", length=255, unique=true)
	 */
	private $name;
	
	/**
	 *
	 * @var int @ORM\Column(name="hp", type="integer")
	 */
	private $hp;
	
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
	 * @return Player
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
	 * Set hp
	 *
	 * @param integer $hp
	 *
	 * @return Player
	 */
	public function setHp($hp)
	{
		$this->hp = $hp;
		
		return $this;
	}
	
	/**
	 * Get hp
	 *
	 * @return int
	 */
	public function getHp()
	{
		return $this->hp;
	}
	public function __toString()
	{
		return $this->name;
	}
}

