<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
    	$service = $this->get('cbsi.services.game');
        $games = $service->getGames();
        //Replacing this code with injected service
    	//$repo = $this->getDoctrine()->getRepository('AppBundle:Game');
		//$games = $repo->findAll();
        return $this->render('default/index.html.twig', array('games' => $games));
    }
}
