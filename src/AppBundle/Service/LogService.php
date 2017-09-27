<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use AppBundle\Entity\Player;
use AppBundle\Entity\Lobby;
use AppBundle\Entity\Log;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Object;

class LogService extends AppService {
	
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
	 * Array of logs
	 * @var array
	 */
	private $logsArray;
	
	/**
	 * Path
	 * @var string
	 */
	private $folder_path;
	
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
		
		$this->logsArray = $this->container->getParameter('Logs');
		$this->folder_path = dirname(__DIR__) . '/Resources/public/battles/';
	}

	/**
	 * Simple log only with player variable
	 * @param string $action
	 * @param Player $player
	 * @param Lobby $lobby
	 * @return string
	 */
	public function simpleLog($action, Player $player, Lobby $lobby)
	{
		$strLog = $this->getRandomLog($action);
		$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName()]);
		$this->createLogEntity($player, $lobby, $strLog, 1);
		return $this->writeLog($strLog);
	}

	/**
	 * Action use log
	 * @param string $action
	 * @param Player $player
	 * @param Lobby $lobby
	 * @param Object $object
	 * @return string
	 */
	public function useLog($action, Player $player, Lobby $lobby, Object $object)
	{
		$strLog = $this->getRandomLog($action);
		$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName(), '$object$' => $object->getName()]);
		$this->createLogEntity($player, $lobby, $strLog, 1);
		return $this->writeLog($strLog);
	}

	/**
	 * Generate log for action kill
	 * @param Player $player
	 * @param Player $playerKill
	 * @param Lobby $lobby
	 */
	public function killLog(Player $player, Player $playerKill, Lobby $lobby, $action = self::ACTION_KILL)
	{
		$strLog = $this->getRandomLog($action);
		if($action == self::ACTION_KILL)
		{
			$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName(), '$player_kill$' => $playerKill->getName()]);
		}
		else if($action == self::ACTION_SELF_KILL)
		{
			$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName()]);
		}
		
		$this->createLogEntity($player, $lobby, $strLog, 1);
		return $this->writeLog($strLog);
	}

	/**
	 * Log for the action fin
	 * @param string $action
	 * @param Player $player
	 * @param Lobby $lobby
	 * @param Object $object
	 * @return string
	 */
	public function findLog($action, Player $player, Lobby $lobby, array $tabObjects = [])
	{
		$strLog = $this->getRandomLog($action);
		switch($action) {
			case self::ACTION_FIND_NO_OBJECT:
				$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName()]);
			break;
			case self::ACTION_FIND_BACKPACK_NO_OBJECT:
				$object = $tabObjects[0];
				
				$nb = ($object->getValue() - 1);
				$pluriel = '';
				if($nb == 2)
				{
					$pluriel = 's';
				}
				
				$params = ['$player$' => $player->getName(), '$object$' => $object->getPronoun() . ' ' . $object->getName(), '$nb$' => $nb, '$pluriel$' => $pluriel];
				$strLog = $this->generateFinalLog($strLog, $params);
			
			break;
			case self::ACTION_FIND_BACKPACK_OBJECT:
				$object_find = $tabObjects[0];
				unset($tabObjects[0]);
				
				$nb = ($object_find->getValue() - 1);
				$pluriel = '';
				if($nb == 2)
				{
					$pluriel = 's';
				}
				
				$str_objects = '';
				foreach( $tabObjects as $object )
				{
					$str_objects .= $object->getPronoun() . ' ' . $object->getName() . ', ';
				}
				$str_objects = substr($str_objects, 0, - 2);
				
				$params = ['$player$' => $player->getName(), '$object_find$' => $object_find->getPronoun() . ' ' . $object_find->getName(), '$object$' => $object->getPronoun() . ' ' . $object->getName(), '$nb$' => $nb, '$pluriel$' => $pluriel];
				$strLog = $this->generateFinalLog($strLog, $params);
			
			break;
			case self::ACTION_FIND:
			case self::ACTION_FIND_LET_OBJECT:
				$object = $tabObjects[0];
				$params = ['$player$' => $player->getName(), '$object$' => $object->getPronoun() . ' ' . $object->getName()];
				$strLog = $this->generateFinalLog($strLog, $params);
			
			break;
			case self::ACTION_FIND_EXCHANGE_OBJECT:
				$object = $tabObjects[0];
				$object_let = $tabObjects[1];
				
				$params = ['$player$' => $player->getName(), '$object_find$' => $object->getPronoun() . ' ' . $object->getName(), '$object_let$' => $object_let->getPronoun() . ' ' . $object_let->getName()];
				$strLog = $this->generateFinalLog($strLog, $params);
			
			break;
			case self::ACTION_FIND_WITH_BACKPACK:
				$backpack = $tabObjects[0];
				$object = $tabObjects[1];
				
				$params = ['$player$' => $player->getName(), '$object$' => $object->getPronoun() . ' ' . $object->getName(), '$backpack$' => $backpack->getName()];
				$strLog = $this->generateFinalLog($strLog, $params);
			break;
			default:
				;
			break;
		}
		$this->createLogEntity($player, $lobby, $strLog, 1);
		return $this->writeLog($strLog);
	}

	/**
	 * Log for the action inventory
	 * @param string $action
	 * @param Player $player
	 * @param Lobby $lobby
	 * @param array $tabObjects
	 * @return string
	 */
	public function inventoryLog($action, Player $player, Lobby $lobby, array $tabObjects = null)
	{
		$strLog = $this->getRandomLog($action);
		switch($action) {
			case self::ACTION_INVENTORY_NO_OBJECT:
				$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName()]);
			break;
			case self::ACTION_INVENTORY:
				$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName(), '$object$' => $tabObjects[0]->getPronoun() . ' ' . $tabObjects[0]->getName()]);
			break;
			case self::ACTION_INVENTORY_BACKPACK_NO_OBJECT:
				$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName(), '$backpack$' => $tabObjects[0]->getName()]);
			break;
			case self::ACTION_INVENTORY_BACKPACK:
				
				$obj = $tabObjects[0];
				
				unset($tabObjects[0]);
				$str_objects = '';
				$nb = 0;
				foreach( $tabObjects as $object )
				{
					if(! is_null($object))
					{
						$and = '';
						if($nb == 1)
						{
							$and = ' et ';
						}
						
						$str_objects .= $and . $object->getPronoun() . ' ' . $object->getName();
						
						$nb ++;
					}
				}
				$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName(), '$backpack$' => $obj->getName(), '$object$' => $str_objects]);
			break;
			default:
				;
			break;
		}
		$this->createLogEntity($player, $lobby, $strLog, 1);
		return $this->writeLog($strLog);
	}

	/**
	 * Generate log for daily statistiques
	 * @param array $stats
	 * @param int $nbDays
	 * @return mixed
	 */
	public function statDayLog(array $stats, $nbDays)
	{
		$nbKill = 0;
		if(isset($stats['days'][$nbDays]['kill']))
		{
			$nbKill = count($stats['days'][$nbDays]['kill']);
		}
		
		if($nbKill > 0)
		{
			$names = '';
			foreach( $stats['days'][$nbDays]['kill'] as $name )
			{
				$names .= $name . ', ';
			}
			$names = substr($names, 0, - 2);
			
			if($nbKill == 1)
			{
				$strLog = $this->logsArray[self::LOG_DAY][self::LOG_DAY_KILL];
				$strLog = $this->generateFinalLog($strLog, ['$player$' => $names, '$day$' => $nbDays]);
			}
			else
			{
				$strLog = $this->logsArray[self::LOG_DAY][self::LOG_DAY_KILLS];
				$strLog = $this->generateFinalLog($strLog, ['$players$' => $names, '$day$' => $nbDays, '$nb_deads$' => $nbKill]);
			}
		}
		else
		{
			$strLog = $this->logsArray[self::LOG_DAY][self::LOG_DAY_NO_KILL];
			$strLog = $this->generateFinalLog($strLog, ['$day$' => $nbDays]);
		}
		return $this->writeLog($strLog);
	}

	/**
	 * Write log when we have a winner
	 * @param Player $player
	 * @param int $nbDays
	 * @return mixed
	 */
	public function winnerLog(Player $player, $nbDays)
	{
		$strLog = $this->logsArray[self::LOG_DAY][self::LOG_DAY_WINNER];
		$strLog = $this->generateFinalLog($strLog, ['$player$' => $player->getName(), '$day$' => $nbDays]);
		if($this->debug)
		{
			$strLog .= "\n-----------------------------------\n";
		}
		return $this->writeLog($strLog);
	}

	/**
	 * Write presentation log
	 * @param Lobby $lobby
	 * @return string
	 */
	public function presentationLog(Lobby $lobby)
	{
		$strLog = $this->getRandomLog(self::ACTION_PRESENTATION);
		$players = '';
		foreach( $lobby->getLobbyPlayers() as $lobbyPLayer )
		{
			$players .= $lobbyPLayer->getPlayer()->getName() . ', ';
		}
		$players = substr($players, 0, - 2);
		$strLog = $this->generateFinalLog($strLog, ['$players$' => $players, '$nb$' => $lobby->getLobbyPlayers()->count()]);
		return $this->writeLog($strLog);
	}

	/**
	 * Write error log
	 * @param string $str
	 */
	public function errorLog($str)
	{
		$this->writeLog($str);
	}

	/**
	 * Write debug log
	 * @param string $str
	 */
	public function debugLog($str)
	{
		$this->writeLog('[DEBUG] ' . $str);
	}

	/**
	 * Generates the final log according to the parameter array
	 * @param string $strLog
	 * @param array $params
	 * @return string
	 */
	private function generateFinalLog($strLog, array $params = [])
	{
		foreach( $params as $key => $value )
		{
			$strLog = str_replace($key, $value, $strLog);
		}
		return $strLog;
	}

	/**
	 * return a log random from a key
	 * @param string $key
	 * @return string
	 */
	private function getRandomLog($key)
	{
		$randKey = array_rand($this->logsArray[$key]);
		return $this->logsArray[$key][$randKey];
	}

	/**
	 * Create entity Log
	 * @param Player $player
	 * @param Lobby $lobby
	 * @param string $strLog
	 * @param int $type
	 */
	private function createLogEntity(Player $player, Lobby $lobby, $strLog, $type)
	{
		$log = new Log();
		$log->setPlayer($player)->setLobby($lobby)->setLabel($strLog)->setType(1);
		$this->doctrine->getManager()->persist($log);
	}

	/**
	 * Write log in a file
	 * @param string $strLog
	 * @return string
	 */
	private function writeLog($strLog)
	{
		$fileLog = fopen($this->folder_path . $this->getFileName(), 'a+');
		fputs($fileLog, $strLog . "\n");
		fclose($fileLog);
		return $strLog;
	}

	/**
	 * Get the name of the log file
	 * @param integer $lobby_id
	 * @return string
	 */
	private function getFileName($lobby_id = null)
	{
		if($lobby_id == null)
		{
			$fileName = 'battle-' . $this->session->get('lobby_id') . '.txt';
		}
		else
		{
			$fileName = 'battle-' . $lobby_id . '.txt';
		}
		
		if($this->debug)
		{
			$fileName = 'battle-demo.txt';
		}
		return $fileName;
	}

	/**
	 * Read log file
	 * @param Lobby $lobby_id
	 * @return array
	 */
	public function readLog($lobby_id)
	{
		$file = $this->folder_path . $this->getFileName();
		if(file_exists($file))
		{
			$logs = file($file);
			return $this->formatTabLog($logs);
		}
		else
		{
			return [];
		}
	}

	/**
	 * Format the logs for display
	 * @param array $logs
	 * @return array
	 */
	private function formatTabLog($logs)
	{
		$result = [];
		$jour = 1;
		foreach( $logs as $log )
		{
			if(preg_match('/Jour/', $log))
			{
				$result['logs'][$jour][] = $log;
				$jour ++;
			}
			else
			{
				$result['logs'][$jour][] = $log;
			}
		}
		return $result;
	}
}