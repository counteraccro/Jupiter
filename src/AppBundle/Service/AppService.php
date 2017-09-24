<?php

namespace AppBundle\Service;

class AppService {
	const ACTION_MOVING = 'moving';
	const ACTION_KILL = 'kill';
	const ACTION_FIND = 'find';
	const ACTION_FIND_WITH_BACKPACK = 'find_with_backpack';
	const ACTION_FIND_LET_OBJECT = 'find_let_object';
	const ACTION_FIND_NO_OBJECT = 'find_no_object';
	const ACTION_FIND_BACKPACK_OBJECT = 'find_backpack_object';
	const ACTION_FIND_BACKPACK_NO_OBJECT = 'find_backpack_no_object';
	const ACTION_FIND_EXCHANGE_OBJECT = 'find_exchange_object';
	const ACTION_SELF_KILL = 'self_kill';
	const ACTION_PRESENTATION = 'presentation';
	const ACTION_INVENTORY = 'inventory';
	const ACTION_INVENTORY_BACKPACK = 'inventory_backpack';
	const ACTION_INVENTORY_BACKPACK_NO_OBJECT = 'inventory_backpack_no_object';
	const ACTION_INVENTORY_NO_OBJECT = 'inventory_no_object';
	const ACTION_WTF = 'wtf';
	const ACTION_VARIOUS = 'various';
	const ACTION_USE = 'use';
	const ACTION_USE_OBJECT_VARIOUS = 'use_object_various';
	const ACTION_USE_OBJECT_GUN = 'use_object_gun';
	const ACTION_USE_OBJECT_RIFLE = 'use_object_rifle';
	const ACTION_USE_OBJECT_CARE = 'use_object_care';
	const ACTION_USE_OBJECT_FOOD = 'use_object_food';
	const ACTION_USE_OBJECT_SHARP = 'use_object_sharp';
	const ACTION_USE_NO_OBJECT = 'use_no_object';
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