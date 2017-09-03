<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GameController extends Controller
{
    /**
     * @Route("battle", name="battle")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Game:index.html.twig', array(
            // ...
        ));
    }

}
