<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Lobby;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
		$this->checkSessionPlayer();
		
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
}
