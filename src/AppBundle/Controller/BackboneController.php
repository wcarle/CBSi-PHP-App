<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use AppBundle\Utils\GameService;
use AppBundle\Entity\Game;

class BackboneController extends Controller
{
    protected $encoders,$normalizers,$serializer;
    public function __construct()
    {
        $this->encoders = array(new JsonEncoder());
        $this->normalizers = array(new GetSetMethodNormalizer());
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }
    /**
     * @Route("/backbone", name="backbone")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        return array();    
    }
    /**
    * @Route("/backbone/api/", name="addGame")
    * @Method("POST")
    */
    public function addGameAction(Request $request)
    {
        $game = null;
        $params = null;
        $content = $this->get("request")->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, false);
        }
        $form = $request->request;
        $service = $this->get('cbsi.services.game');
        $game = $service->addGame(
            $params->name, 
            $params->platform, 
            $params->description);

        $response = new Response($this->serializer->serialize($game, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/backbone/api/all", name="allGames")
     * @Method("GET")
     */
    public function allGamesAction()
    {
        $service = $this->get('cbsi.services.game');
        $games = $service->getGames();
        $response = new Response($this->serializer->serialize($games, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/backbone/api/{id}", name="getGame")
     * @Method("GET")
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
        $response = new Response($this->serializer->serialize($game, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/backbone/api/{id}", name="updateGame")
     * @Method("PUT")
     */
    public function updateGameAction(Request $request, $id)
    {
        $game = null;
        $params = null;
        $content = $this->get("request")->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, false);
        }
        $form = $request->request;
        $service = $this->get('cbsi.services.game');
        
        $game = $service->updateGame(
            $params->id, 
            $params->name, 
            $params->platform, 
            $params->description);
    
        $response = new Response($this->serializer->serialize($game, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/backbone/api/{id}", name="removeGame")
     * @Method("DELETE")
     */
    public function removeGameAction($id)
    {
        $service = $this->get('cbsi.services.game');
        $service->removeGame($id);
        return new Response("true");
    }

}
