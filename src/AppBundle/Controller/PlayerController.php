<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;

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
		if(! $request->isXmlHttpRequest())
		{
			//return new Response('This is not ajax!', 400);
		}
		
		$playerGenerator = $this->container->get('app.players_generator');
		$tabPlayers = $playerGenerator->generatePlayers(5);
		
		return new JsonResponse(array (
				'data' => $this->serializer($tabPlayers)
		));
	}
}
