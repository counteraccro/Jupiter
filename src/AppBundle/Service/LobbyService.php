<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Player;
use AppBundle\Entity\Lobby;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\LobbyPlayer;

class LobbyService {
	
	/**
	 *
	 * @var Doctrine
	 */
	private $doctrine;
	
	/**
	 *
	 * @var Container
	 */
	private $container;
	
	/**
	 * 
	 * @var Session
	 */
	private $session;
	
	/**
	 *
	 * @param Doctrine $doctrine
	 * @param Container $container
	 */
	public function __construct(Doctrine $doctrine, Container $container, Session $session)
	{
		$this->doctrine = $doctrine;
		$this->container = $container;
		$this->session = $session;
	}
	
	/**
	 * Create new lobby.
	 * If player is not null, add to the lobby
	 * @param Player $player
	 */
	public function createLobby(Player $player = null)
	{
		$lobby = new Lobby();
		$lobby->setName('Name');
		
		$this->doctrine->getManager()->persist($lobby);
		$this->doctrine->getManager()->flush();
		$lobby->setName('Lobby n° ' . $lobby->getId());
		
		
		if(!is_null($player))
		{
			$lobbyPlayer = new LobbyPlayer();
			$lobbyPlayer->setLobby($lobby)->setPlayer($player);
			$lobby->addLobbyPlayer($lobbyPlayer);
			
			$this->doctrine->getManager()->persist($lobbyPlayer);
			$this->doctrine->getManager()->persist($lobby);
		}
		
		$this->doctrine->getManager()->flush();
		
		$this->session->set('lobby_id', $lobby->getId());
		
		return $lobby;
	}
}