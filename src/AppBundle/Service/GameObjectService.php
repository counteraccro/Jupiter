<?php
namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\LobbyPlayer;

class GameObjectService {
	
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
	 * @var LogService
	 */
	private $logService;
	
	/**
	 *
	 * @param Doctrine $doctrine
	 * @param Container $container
	 * @param LogService $log
	 */
	public function __construct(Doctrine $doctrine, Container $container, LogService $logService)
	{
		$this->doctrine = $doctrine;
		$this->container = $container;
		$this->logService = $logService;
		
	}
	
	/**
	 * Action find object
	 * @param LobbyPlayer $lobbyPlayer
	 */
	public function findObjectAction(LobbyPlayer $lobbyPlayer)
	{
		//$object = $this->doctrine->getRepository("AppBundle:Object")->getRandomObject();
	}
}