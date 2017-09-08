<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LobbyPlayer
 *
 * @ORM\Table(name="lobby_player")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LobbyPlayerRepository")
 */
class LobbyPlayer
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lobby", inversedBy="lobbyPlayers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lobby;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Player", inversedBy="lobbyPlayers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_kill", type="integer")
     */
    private $nbKill;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_dead", type="boolean")
     */
    private $isDead;

    
    public function __construct()
    {
    	$this->nbKill = 0;
    	$this->isDead = false;
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
     * Set nbKill
     *
     * @param integer $nbKill
     *
     * @return LobbyPlayer
     */
    public function setNbKill($nbKill)
    {
        $this->nbKill = $nbKill;

        return $this;
    }

    /**
     * Get nbKill
     *
     * @return int
     */
    public function getNbKill()
    {
        return $this->nbKill;
    }

    /**
     * Set isDead
     *
     * @param boolean $isDead
     *
     * @return LobbyPlayer
     */
    public function setIsDead($isDead)
    {
        $this->isDead = $isDead;

        return $this;
    }

    /**
     * Get isDead
     *
     * @return bool
     */
    public function getIsDead()
    {
        return $this->isDead;
    }

    /**
     * Set lobby
     *
     * @param \AppBundle\Entity\Lobby $lobby
     *
     * @return LobbyPlayer
     */
    public function setLobby(\AppBundle\Entity\Lobby $lobby)
    {
        $this->lobby = $lobby;

        return $this;
    }

    /**
     * Get lobby
     *
     * @return \AppBundle\Entity\Lobby
     */
    public function getLobby()
    {
        return $this->lobby;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return LobbyPlayer
     */
    public function setPlayer(\AppBundle\Entity\Player $player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
