<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\LobbyPlayer;
use AppBundle\Entity\Lobby;

class LobbyController extends AppController {

	/**
	 * @Route("/index_lobby")
	 */
	public function indexAction()
	{
		return $this->render('AppBundle:Lobby:index.html.twig', array (
			// ...
		));
	}

	/**
	 * find an open lobby
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
			
			if(! empty($lp))
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
			
			$this->get('session')->set('lobby_id', $lobby->getId());
			
			return new JsonResponse(array (
					'data' => $this->serializer(array (
							'response' => 'success',
							'lobby_id' => $lobby->getId() 
					)) 
			));
		}
	}

	/**
	 * Waiting new players
	 * @Route("battle/ajax_wait_lobby/{lobby_id}", name="ajax_wait_lobby")
	 * @ParamConverter("lobby", options={"mapping": {"lobby_id": "id"}})
	 */
	public function ajaxWaitInLobby(Request $request, Lobby $lobby)
	{
		$return = $this->isAjaxRequest($request);
		if(is_object($return))
		{
			//return $return;
		}
		
		if(! $this->get('session')->has('lobby_id') || $this->get('session')->get('lobby_id') != $lobby->getId())
		{
			return $this->redirect($this->generateUrl('homepage'));
		}
		
		/*return new JsonResponse(array (
				'data' => $this->serializer($lobby) 
		));*/
		
		return $this->render('AppBundle:Lobby:ajax_waiting_lobby.html.twig', array (
				'lobby' => $lobby
		));
	}
}
