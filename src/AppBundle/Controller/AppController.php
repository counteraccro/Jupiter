<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppController extends Controller {

	/**
	 * serialize mixed data to JSON or XML
	 * @param mixed $dataToSerialize
	 * @param string $encoder, default : json
	 * @return string
	 */
	protected function serializer($dataToSerialize, $encoder = 'json')
	{
		// Initialize encoder
		$encoders = array (
				new XmlEncoder(),
				new JsonEncoder() 
		);
		$normalizer = new ObjectNormalizer();
		$normalizer->setCircularReferenceLimit(2);
		
		$normalizer->setCircularReferenceHandler(function ($object) {
			return $object->getId();
		});
		
		$serializer = new Serializer(array($normalizer), $encoders);
		return $serializer->serialize($dataToSerialize, $encoder);
	}
	
	/**
	 * Check if the request is Ajax or not
	 * @param Request $request
	 * @return boolean
	 */
	protected function isAjaxRequest(Request $request)
	{
		if(! $request->isXmlHttpRequest())
		{
			return new Response('This is not ajax!', 400);
		}
		return true;
	}
}