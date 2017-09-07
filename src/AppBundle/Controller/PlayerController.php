<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlayerController extends AppController {

	/**
	 * @Route("/index")
	 */
	public function indexAction()
	{
		return $this->render('AppBundle:Player:index.html.twig', array (
			// ...
		));
	}

	/**
	 * @Route("/ajax_generate_players", name="ajax_generate_players")
	 *
	 * @param Request $request
	 */
	public function ajaxGeneratePlayers(Request $request)
	{
	
		$return = $this->isAjaxRequest($request);
		if(is_object($return))
		{
			return $return;
		}
		
		$playerGeneratorService = $this->container->get('app.players_generator');
		$tabPlayers = $playerGeneratorService->generatePlayers(5);
		
		return new JsonResponse(array (
				'data' => $this->serializer($tabPlayers)
		));
	}
}
