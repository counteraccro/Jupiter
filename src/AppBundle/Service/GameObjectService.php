<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\LobbyPlayer;
use AppBundle\Entity\Lobby;

class GameObjectService extends AppService {
	
	const OBJECT_SEAT = 'SEAT';
	
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
	public function findObjectAction(LobbyPlayer $lobbyPlayer, Lobby $lobby)
	{
		$rand = rand(1, 100);
		
		// possibility of finding nothing
		if($rand <= 20)
		{
			$this->logService->findLog(self::ACTION_FIND_NO_ITEM, $lobbyPlayer->getPlayer(), $lobby);
			return true;
		}
		
		// search for a random object
		$object = $this->doctrine->getRepository("AppBundle:Object")->getRandomObject();
		
		//1st case the player has no object on him
		if(is_null($lobbyPlayer->getObject1()))
		{
			//It finds a storage object
			$lobbyPlayer->setObject1($object);
			$this->doctrine->getManager()->persist($lobbyPlayer);
			$this->doctrine->getManager()->flush();
			
			if($object->getType() == self::OBJECT_SEAT)
			{
				$this->logService->findLog(self::ACTION_FIND_STOCKAGE_NO_ITEM, $lobbyPlayer->getPlayer(), $lobby, $object);
			}
			// It finds a classic object
			else {
				$this->logService->findLog(self::ACTION_FIND, $lobbyPlayer->getPlayer(), $lobby, $object);
			}
			return true;
		}
		
		$this->logService->errorLog('Action inconnu');
	}
}