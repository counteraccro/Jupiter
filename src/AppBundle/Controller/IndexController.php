<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Index:index.html.twig', array(
            // ...
        ));
    }
    
    /**
     * @Route("/ajax_game_menu", name="ajax_game_menu")
     */
    public function AjaxGameMenuAction()
    {
    	return $this->render('AppBundle:Index:ajax_game_menu.html.twig', array(
    			// ...
    	));
    }

}
