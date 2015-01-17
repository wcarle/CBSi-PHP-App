<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Utils\GameService;
use AppBundle\Entity\Game;

class GamesController extends Controller
{
    /**
     * @Route("/games/")
     * @Template()
     */
    public function gamesAction()
    {

        $service = $this->get('cbsi.services.game');
        $games = $service->getGames();
        //Replacing this code with injected service
        //$repo = $this->getDoctrine()->getRepository('AppBundle:Game');
        //$games = $repo->findAll();
        return array(
                'games' => $games
            );    
    }
    /**
     * @Route("/game/{id}")
     * @Template()
     */
    public function gameAction($id)
    {
        $game = null;
        if($id == 0)
        {
            $game = new Game();
        }
        else
        {
            $service = $this->get('cbsi.services.game');
            $game = $service->getGame($id);
        }
        return array(
                'game' => $game
            );    
    }
    /**
     * @Route("/games/SaveGame")
     */
    public function saveGameAction(Request $request)
    {
        $form = $request->request;
        $service = $this->get('cbsi.services.game');
        $service->updateGame(
            $form->get('id'), 
            $form->get('name'), 
            $form->get('platform'), 
            $form->get('description'));
        return $this->redirect('/games');
    }
        /**
     * @Route("/games/AddGame")
     */
    public function addGameAction(Request $request)
    {
        $form = $request->request;
        $service = $this->get('cbsi.services.game');
        $service->addGame(
            $form->get('name'), 
            $form->get('platform'), 
            $form->get('description'));
        return $this->redirect('/games');
    }
    /**
     * @Route("/games/RemoveGame/{id}")
     */
    public function removeGameAction($id)
    {
        $service = $this->get('cbsi.services.game');
        $service->removeGame($id);
        return $this->redirect('/games');
    }

}
