<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Player;
use AppBundle\Form\PlayerType;

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

	/**
	 * @Route("/ajax_new_player_form", name="ajax_new_player_form")
	 *
	 * @param Request $request
	 */
	public function ajaxNewPlayerForm(Request $request)
	{
		$return = $this->isAjaxRequest($request);
		if(is_object($return))
		{
			return $return;
		}
		
		$player = new Player();
		$form = $this->get('form.factory')->create(PlayerType::class, $player);
		
		if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$player->setHp(100);
			$em = $this->getDoctrine()->getManager();
			$em->persist($player);
			$em->flush();
			
			$this->get('session')->set('player_id', $player->getId());
			
			return new JsonResponse(array (
					'data' => $this->serializer(array('response' => 'success'))
			));
		}
		return $this->render('AppBundle:Player:ajax_new_player_form.html.twig', array (
				'form' => $form->createView() 
		));
	}
}
