<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Player;
use AppBundle\Entity\Lobby;
use AppBundle\Entity\LobbyPlayer;

class PlayersGeneratorService {
	
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
	 * @param Doctrine $doctrine
	 * @param Container $container
	 */
	public function __construct(Doctrine $doctrine, Container $container)
	{
		$this->doctrine = $doctrine;
		$this->container = $container;
	}

	/**
	 * Generate player for lobby
	 * @param Lobby $lobby
	 * @return \AppBundle\Entity\Lobby
	 */
	public function generatePlayers(Lobby $lobby)
	{
		$first_nameArray = $this->container->getParameter('First_name');
		$first_nameArray = explode(' ', $first_nameArray);
		
		$nb_players = ($lobby->getNbPlaceMax() - $lobby->getLobbyPlayers()->count());
		
		for($i = 0; $i < $nb_players; $i ++)
		{
			$key = array_rand($first_nameArray);
			$first_name = $first_nameArray [$key];
			
			$player = new Player();
			$player->setName($first_name);
			$player->setHp(100);
			
			$lobbyPlayer = new LobbyPlayer();
			$lobbyPlayer->setPlayer($player);
			$lobbyPlayer->setIsDead(false)->setNbKill(0);
			$lobbyPlayer->setLobby($lobby);
			
			$this->doctrine->getManager()->persist($player);
			$this->doctrine->getManager()->persist($lobbyPlayer);
		}
		
		$lobby->setStatus('CLOSE');
		$this->doctrine->getManager()->persist($lobby);
		
		$this->doctrine->getManager()->flush();
		
		return $lobby;
	}
}