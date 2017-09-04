<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Lobby;

class Game {
	
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
	
	public function generateGame(Lobby $lobby)
	{
		// get Logs
		$logsArray = $this->container->getParameter('Logs');
		
		return $lobby;
	}
}