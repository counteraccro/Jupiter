<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GameController extends Controller {

	/**
	 * @Route("battle", name="battle")
	 */
	public function indexAction()
	{
		/*$em = $this->getDoctrine()->getManager();
		
		$test = $em->getRepository('AppBundle:Lobby')->findAll();
		
		foreach( $test as $t )
		{
			echo '<br />--------------------------' . $t->getName() . ' n° ' . $t->getId() . '---------------------------';
			foreach( $t->getLobbyPlayers() as $lp )
			{
				echo '<br /> ----> LobbyPlayer id' . $lp->getId() . 
				"<br /> ---------> Player : " . $lp->getPlayer()->getId() . " " . $lp->getPlayer()->getName();
			}
		}
		
		$player = $em->getRepository('AppBundle:Player')->findById(280);
		$player = $player[0];
		echo '<br /><br /><br />------------------------------------------------------------------------';
		
		echo $player->getName() . '<br />';
		foreach($player->getLobbyPlayers() as $lp)
		{
			echo '<br /> ----> LobbyPlayer id' . $lp->getId() .
			"<br /> ---------> Loby : " . $lp->getLobby()->getId() . " " . $lp->getLobby()->getName();
		}*/
		
		return $this->render('AppBundle:Game:index.html.twig', array (
			// ...
		));
	}
}
