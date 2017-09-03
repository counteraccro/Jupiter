<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Player;

class PlayersGenerator {
	
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
	 * 
	 * @param int $nb_players
	 * @return \AppBundle\Entity\Player[]
	 */
	public function generatePlayers($nb_players)
	{
		$first_nameArray = $this->container->getParameter('First_name');
		$first_nameArray = explode(' ', $first_nameArray);
		$tabPlayer = [];
		
		for($i = 0; $i < $nb_players; $i ++)
		{
			$key = array_rand($first_nameArray);
			$first_name = $first_nameArray[$key];
			
			$player = new Player();
			$player->setName($first_name);
			$player->setHp(100);
			
			$this->doctrine->getManager()->persist($player);
			$tabPlayer[] = $player;
		}
		$this->doctrine->getManager()->flush();
		
		return $tabPlayer;
	}
}