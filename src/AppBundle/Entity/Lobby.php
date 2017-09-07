<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * Lobby
 *
 * @ORM\Table(name="lobby")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LobbyRepository")
 */
class Lobby {
	const STATUS_OPEN = 'OPEN';
	const STATUS_CLOSE = 'CLOSE';
	
	/**
	 *
	 * @var int @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\LobbyPlayer", mappedBy="lobby")
	 */
	private $lobbyPlayers;
	
	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Log", mappedBy="lobby")
	 */
	private $logs;
	
	/**
	 *
	 * @var string @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="string", name="status", options={"default": "CLOSE"})
	 */
	private $status;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->lobbyPlayers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->status = self::STATUS_OPEN;
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
	 * @return Lobby
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
	 * Add lobbyPlayer
	 *
	 * @param \AppBundle\Entity\LobbyPlayer $lobbyPlayer
	 *
	 * @return Lobby
	 */
	public function addLobbyPlayer(\AppBundle\Entity\LobbyPlayer $lobbyPlayer)
	{
		$this->lobbyPlayers[] = $lobbyPlayer;
		
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
	 * @return Lobby
	 */
	public function addLog(\AppBundle\Entity\Log $log)
	{
		$this->logs[] = $log;
		
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

	/**
	 * Set status
	 *
	 * @param string $status
	 *
	 * @return Lobby
	 */
	public function setStatus($status)
	{
		if(! in_array($status, array (
				self::STATUS_OPEN,
				self::STATUS_CLOSE 
		)))
		{
			throw new \InvalidArgumentException("Invalid status");
		}
		
		$this->status = $status;
		
		return $this;
	}

	/**
	 * Get status
	 *
	 * @return string
	 */
	public function getStatus()
	{
		return $this->status;
	}
}
