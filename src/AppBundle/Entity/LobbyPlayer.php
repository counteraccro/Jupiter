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
    
    /**
     * @var int
     *
     * @ORM\Column(name="hp", type="integer")
     */
    private $hp;
    
    /**
     * Possible action after completion of certain actions
     * @var string @ORM\Column(name="next_action_possible", type="string", length=100)
     */
    private $nextActionPossible;
    
    /**
     * History of actions 
     * @var string @ORM\Column(name="last_action", type="text")
     */
    private $lastActions;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Player", cascade={"all"})
     */
    private $playerFollow;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Player", cascade={"all"})
     */
    private $teammate;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Object", cascade={"all"})
     */
    private $object_1;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Object", cascade={"all"})
     */
    private $object_2;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Object", cascade={"all"})
     */
    private $object_3;

    
    public function __construct()
    {
    	$this->nbKill = 0;
    	$this->isDead = false;
    	$this->hp = 100;
    	$this->nextActionPossible = '';
    	$this->lastActions = '';
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

    /**
     * Set hp
     *
     * @param integer $hp
     *
     * @return LobbyPlayer
     */
    public function setHp($hp)
    {
        $this->hp = $hp;

        return $this;
    }

    /**
     * Get hp
     *
     * @return integer
     */
    public function getHp()
    {
        return $this->hp;
    }

    /**
     * Set object1
     *
     * @param \AppBundle\Entity\Object $object1
     *
     * @return LobbyPlayer
     */
    public function setObject1(\AppBundle\Entity\Object $object1 = null)
    {
        $this->object_1 = $object1;

        return $this;
    }

    /**
     * Get object1
     *
     * @return \AppBundle\Entity\Object
     */
    public function getObject1()
    {
        return $this->object_1;
    }

    /**
     * Set object2
     *
     * @param \AppBundle\Entity\Object $object2
     *
     * @return LobbyPlayer
     */
    public function setObject2(\AppBundle\Entity\Object $object2 = null)
    {
        $this->object_2 = $object2;

        return $this;
    }

    /**
     * Get object2
     *
     * @return \AppBundle\Entity\Object
     */
    public function getObject2()
    {
        return $this->object_2;
    }

    /**
     * Set object3
     *
     * @param \AppBundle\Entity\Object $object3
     *
     * @return LobbyPlayer
     */
    public function setObject3(\AppBundle\Entity\Object $object3 = null)
    {
        $this->object_3 = $object3;

        return $this;
    }

    /**
     * Get object3
     *
     * @return \AppBundle\Entity\Object
     */
    public function getObject3()
    {
        return $this->object_3;
    }

    /**
     * Set nextActionPossible
     *
     * @param string $nextActionPossible
     *
     * @return LobbyPlayer
     */
    public function setNextActionPossible($nextActionPossible)
    {
        $this->nextActionPossible = $nextActionPossible;

        return $this;
    }

    /**
     * Get nextActionPossible
     *
     * @return string
     */
    public function getNextActionPossible()
    {
        return $this->nextActionPossible;
    }

    /**
     * Set playerFollow
     *
     * @param \AppBundle\Entity\Player $playerFollow
     *
     * @return LobbyPlayer
     */
    public function setPlayerFollow(\AppBundle\Entity\Player $playerFollow = null)
    {
        $this->playerFollow = $playerFollow;

        return $this;
    }

    /**
     * Get playerFollow
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayerFollow()
    {
        return $this->playerFollow;
    }

    /**
     * Set teammate
     *
     * @param \AppBundle\Entity\Player $teammate
     *
     * @return LobbyPlayer
     */
    public function setTeammate(\AppBundle\Entity\Player $teammate = null)
    {
        $this->teammate = $teammate;

        return $this;
    }

    /**
     * Get teammate
     *
     * @return \AppBundle\Entity\Player
     */
    public function getTeammate()
    {
        return $this->teammate;
    }

    /**
     * Set lastActions
     *
     * @param string $lastActions
     *
     * @return LobbyPlayer
     */
    public function setLastActions($lastActions)
    {
        $this->lastActions = $lastActions;

        return $this;
    }

    /**
     * Get lastActions
     *
     * @return string
     */
    public function getLastActions()
    {
        return $this->lastActions;
    }
}
