<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lobby
 *
 * @ORM\Table(name="lobby")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LobbyRepository")
 */
class Lobby
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
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
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\LobbyPlayer", mappedBy="lobby")
    */
    private $lobbyPlayers;

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
     * Constructor
     */
    public function __construct()
    {
        $this->lobbyPlayers = new \Doctrine\Common\Collections\ArrayCollection();
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
}
