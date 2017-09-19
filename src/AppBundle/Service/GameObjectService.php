<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\LobbyPlayer;
use AppBundle\Entity\Lobby;

class GameObjectService extends AppService {
	const OBJECT_BACKPACK = 'BACKPACK';
	
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
		$rand = mt_rand(1, 100);
		
		// possibility of finding nothing
		if($rand <= 20)
		{
			$this->logService->findLog(self::ACTION_FIND_NO_OBJECT, $lobbyPlayer->getPlayer(), $lobby);
			return true;
		}
		
		// search for a random object
		$object = $this->doctrine->getRepository("AppBundle:Object")->getRandomObject();
		
		// 1st case the player has no object on him
		if(is_null($lobbyPlayer->getObject1()))
		{
			$lobbyPlayer->setObject1($object);
			$this->doctrine->getManager()->persist($lobbyPlayer);
			$this->doctrine->getManager()->flush();
			
			// It finds a storage object
			if($object->getType() == self::OBJECT_BACKPACK)
			{
				$this->logService->findLog(self::ACTION_FIND_BACKPACK_NO_OBJECT, $lobbyPlayer->getPlayer(), $lobby, array($object 
				));
			}
			// It finds a classic object
			else
			{
				$this->logService->findLog(self::ACTION_FIND, $lobbyPlayer->getPlayer(), $lobby, array($object 
				));
			}
			return true;
		}
		
		// 2nd case it already has an object and has no extra place
		if(is_null($lobbyPlayer->getObject2()) && $lobbyPlayer->getObject1()->getType() != self::OBJECT_BACKPACK)
		{
			// 3rd case, he finds a bag and so now has an extra space
			if($object->getType() == self::OBJECT_BACKPACK)
			{
				$lobbyPlayer->setObject2($lobbyPlayer->getObject1());
				$lobbyPlayer->setObject1($object);
				$this->logService->findLog(self::ACTION_FIND_BACKPACK_OBJECT, $lobbyPlayer->getPlayer(), $lobby, array($object,$lobbyPlayer->getObject2() 
				));
			}
			else
			{
				// we verify the interest of the object
				if($object->getCategoryObject()->getPriority() >= $lobbyPlayer->getObject1()->getCategoryObject()->getPriority())
				{
					$rand = mt_rand(1, 100);
					
					// 80% chance to exchange it if the interest is greater
					if($rand > 20)
					{
						$object_let = $lobbyPlayer->getObject1();
						$lobbyPlayer->setObject1($object);
						$this->logService->findLog(self::ACTION_FIND_EXCHANGE_OBJECT, $lobbyPlayer->getPlayer(), $lobby, array($object,$object_let 
						));
					}
					else
					{
						$this->logService->findLog(self::ACTION_FIND_LET_OBJECT, $lobbyPlayer->getPlayer(), $lobby, array($object 
						));
					}
				}
				else
				{
					$this->logService->findLog(self::ACTION_FIND_LET_OBJECT, $lobbyPlayer->getPlayer(), $lobby, array($object 
					));
				}
			}
			
			$this->doctrine->getManager()->persist($lobbyPlayer);
			$this->doctrine->getManager()->flush();
			return true;
		}
		
		// 4th case, it already has an object and an additional space
		if(is_null($lobbyPlayer->getObject2()) && $lobbyPlayer->getObject1()->getType() == self::OBJECT_BACKPACK)
		{
			$lobbyPlayer->setObject2($object);
			$this->logService->findLog(self::ACTION_FIND_WITH_BACKPACK, $lobbyPlayer->getPlayer(), $lobby, array($lobbyPlayer->getObject1(),$object 
			));
			
			$this->doctrine->getManager()->persist($lobbyPlayer);
			$this->doctrine->getManager()->flush();
			
			return true;
		}
		
		// 5th case, it already has an object and an additional space for item 3
		if(is_null($lobbyPlayer->getObject3()) && $lobbyPlayer->getObject1()->getType() == self::OBJECT_BACKPACK)
		{
			$lobbyPlayer->setObject3($object);
			$this->logService->findLog(self::ACTION_FIND_WITH_BACKPACK, $lobbyPlayer->getPlayer(), $lobby, array($lobbyPlayer->getObject1(),$object 
			));
			
			$this->doctrine->getManager()->persist($lobbyPlayer);
			$this->doctrine->getManager()->flush();
			
			return true;
		}
		else
		{
			
			$tabObject = array(2 => $lobbyPlayer->getObject2(),3 => $lobbyPlayer->getObject3() 
			);
			
			foreach( $tabObject as $key => $object_ )
			{
				// we verify the interest of the object
				if($object->getCategoryObject()->getPriority() >= $object_->getCategoryObject()->getPriority())
				{
					$rand = mt_rand(1, 100);
					
					// 80% chance to exchange it if the interest is greater
					if($rand > 20)
					{
						$getObject = 'getObject' . $key;
						$object_let = $lobbyPlayer->{$getObject}();
						$lobbyPlayer->{$getObject}($object);
						$this->logService->findLog(self::ACTION_FIND_EXCHANGE_OBJECT, $lobbyPlayer->getPlayer(), $lobby, array($object,$object_let 
						));
						
						$this->doctrine->getManager()->persist($lobbyPlayer);
						$this->doctrine->getManager()->flush();
					}
					else
					{
						$this->logService->findLog(self::ACTION_FIND_LET_OBJECT, $lobbyPlayer->getPlayer(), $lobby, array($object 
						));
					}
					return true;
				}
			}
			
			$this->logService->findLog(self::ACTION_FIND_LET_OBJECT, $lobbyPlayer->getPlayer(), $lobby, array($object 
			));
			return true;
		}
		
		$this->logService->errorLog('Error in ' . __METHOD__ . ' Object : ' . $lobbyPlayer->getObject1()->getName());
	}
}