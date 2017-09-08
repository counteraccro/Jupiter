<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Lobby;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\LobbyPlayer;

class GameController extends AppController {

	/**
	 * @Route("battle", name="battle")
	 */
	public function indexAction()
	{
		/*
		 * $em = $this->getDoctrine()->getManager();
		 * $test = $em->getRepository('AppBundle:Lobby')->findAll();
		 * foreach( $test as $t )
		 * {
		 * echo '<br />--------------------------' . $t->getName() . ' n° ' . $t->getId() . '---------------------------';
		 * foreach( $t->getLobbyPlayers() as $lp )
		 * {
		 * echo '<br /> ----> LobbyPlayer id' . $lp->getId() .
		 * "<br /> ---------> Player : " . $lp->getPlayer()->getId() . " " . $lp->getPlayer()->getName();
		 * }
		 * }
		 * $player = $em->getRepository('AppBundle:Player')->findById(280);
		 * $player = $player[0];
		 * echo '<br /><br /><br />------------------------------------------------------------------------';
		 * echo $player->getName() . '<br />';
		 * foreach($player->getLobbyPlayers() as $lp)
		 * {
		 * echo '<br /> ----> LobbyPlayer id' . $lp->getId() .
		 * "<br /> ---------> Loby : " . $lp->getLobby()->getId() . " " . $lp->getLobby()->getName();
		 * }
		 */
		if(! $this->get('session')->has('player_id'))
		{
			return $this->redirect($this->generateUrl('homepage'));
		}
		
		return $this->render('AppBundle:Game:index.html.twig', array (
			// ...
		));
	}

	/**
	 * @Route("battle/ajax_game/{lobby_id}", name="ajax_game")
	 * @ParamConverter("lobby", options={"mapping": {"lobby_id": "id"}})
	 */
	public function ajaxGameAction(Request $request, Lobby $lobby)
	{
		$return = $this->isAjaxRequest($request);
		if(is_object($return))
		{
			return $return;
		}
		
		$gameService = $this->container->get('app.game');
		$result = $gameService->generateGame($lobby);
		
		return new JsonResponse(array (
				'data' => $this->serializer($result) 
		));
	}

	/**
	 * @Route("find_open_lobby", name="find_open_lobby")
	 */
	public function ajaxFindOpenLoobyAction(Request $request)
	{
		$return = $this->isAjaxRequest($request);
		if(is_object($return))
		{
			return $return;
		}
		
		$em = $this->getDoctrine()->getManager();
		$lobby = $em->getRepository('AppBundle:Lobby')->findFirstLobbyByStatus();
		
		if(empty($lobby))
		{
			return new JsonResponse(array (
					'data' => $this->serializer(array (
							'response' => 'no_lobby' 
					)) 
			));
		}
		else
		{
			$player = $em->getRepository('AppBundle:Player')->findById($this->get('session')->get('player_id'))[0];
			
			$lp = $em->getRepository('AppBundle:LobbyPlayer')->findLobbyPlayer($player, $lobby);
			
			if(!empty($lp))
			{
				return new JsonResponse(array (
						'data' => $this->serializer(array (
								'response' => 'critique_error',
								'text' => 'Vous êtes déjà présent sur cette session !'
						))
				));
			}
			
			$lobbyPlayer = new LobbyPlayer();
			$lobbyPlayer->setPlayer($player);
			$lobbyPlayer->setLobby($lobby);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($lobbyPlayer);
			$em->flush();
			
			return new JsonResponse(array (
					'data' => $this->serializer(array (
							'response' => 'success'
					))
			));
		}
	}
}
