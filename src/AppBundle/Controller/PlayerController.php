<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlayerController extends Controller {

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
		if(!$request->isXmlHttpRequest())
		{
			//return new Response('This is not ajax!', 400);
		}
		
		$playerGenerator = $this->container->get('app.players_generator');
		$tabPlayers = $playerGenerator->generatePlayers(5);
		
		// Initialize encoder
		$encoders = array(new XmlEncoder(), new JsonEncoder());
		$normalizers = array(new ObjectNormalizer());
		
		$serializer = new Serializer($normalizers, $encoders);
		$json = $serializer->serialize($tabPlayers, 'json');
		
		return new JsonResponse(array('data' => $json));
	}
}
