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
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\LobbyPlayer", mappedBy="player")
	 */
	private $lobbyPlayers;
	
	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Log", mappedBy="lobby")
	 */
	private $logs;
	
	/**
	 *
	 * @var string @Assert\NotBlank()
	 *      @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;
	
	/**
	 *
	 * @var int @ORM\Column(name="hp", type="integer")
	 */
	private $hp;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->lobbyPlayers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hp = 100;
	}

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

	/**
	 * Add lobbyPlayer
	 *
	 * @param \AppBundle\Entity\LobbyPlayer $lobbyPlayer
	 *
	 * @return Player
	 */
	public function addLobbyPlayer(\AppBundle\Entity\LobbyPlayer $lobbyPlayer)
	{
		$this->lobbyPlayers [] = $lobbyPlayer;
		
		return $this;
	}

	/**
	 * Remove lobbyPlayer
	 *
	 * @param \AppBundle\Entity\LobbyPlayer $lobbyPlayer
	 */
	public function removeLobbyPlayer(\AppBundle\Entity\LobbyPlayer $lobbyPlayer)
	{
		$this->lobbyPlayers->removeElement($lobbyPlayer);
	}

	/**
	 * Get lobbyPlayers
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getLobbyPlayers()
	{
		return $this->lobbyPlayers;
	}

	/**
	 * Add log
	 *
	 * @param \AppBundle\Entity\Log $log
	 *
	 * @return Player
	 */
	public function addLog(\AppBundle\Entity\Log $log)
	{
		$this->logs [] = $log;
		
		return $this;
	}

	/**
	 * Remove log
	 *
	 * @param \AppBundle\Entity\Log $log
	 */
	public function removeLog(\AppBundle\Entity\Log $log)
	{
		$this->logs->removeElement($log);
	}

	/**
	 * Get logs
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getLogs()
	{
		return $this->logs;
	}
}
