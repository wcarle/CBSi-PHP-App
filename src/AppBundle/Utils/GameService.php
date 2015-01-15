<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Game;
use Doctrine\ORM\EntityManager;

class GameService
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

	public function getGames()
	{
		$repo = $this->em->getRepository('AppBundle:Game');
		$games = $repo->findAll();
		return $games;
	}
    public function addGame($name, $platform, $description)
    {
        $game = new Game();
        $game->setName($name);
        $game->setPlatform($platform);
        $game->setDescription($description);

        $this->em->persist($game);
        $this->em->flust();
        return $game;
    }
}