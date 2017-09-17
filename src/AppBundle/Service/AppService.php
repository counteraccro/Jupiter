<?php

namespace AppBundle\Service;

class AppService {
	const ACTION_MOVING = 'moving';
	const ACTION_KILL = 'kill';
	const ACTION_FIND = 'find';
	const ACTION_FIND_LET_OBJECT = 'find_let_object';
	const ACTION_FIND_NO_OBJECT = 'find_no_object';
	const ACTION_FIND_STOCKAGE_OBJECT = 'find_stockage_object';
	const ACTION_FIND_EXCHANGE_OBJECT = 'find_exchange_object';
	const ACTION_SELF_KILL = 'self_kill';
	const ACTION_PRESENTATION = 'presentation';
	const ACTION_FIND_STOCKAGE_NO_OBJECT = 'find_stockage_no_object';
	
	const LOG_DAY = 'log_day';
	const LOG_DAY_NO_KILL = 'no_kill';
	const LOG_DAY_KILL = 'kill';
	const LOG_DAY_KILLS = 'kills';
	const LOG_DAY_WINNER = 'winner';
	
	/**
	 * Maximum number of days of game duration 
	 * @var integer
	 */
	const MAX_DAY_GAME = 15;
	
	/**
	 * debug mode
	 * @var string
	 */
	protected $debug = true;
}