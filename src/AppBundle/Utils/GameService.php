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
    public function getGame($id)
    {
        $repo = $this->em->getRepository('AppBundle:Game');
        $game = $repo->find($id);
        return $game;
    }
    public function addGame($name, $platform, $description)
    {
        $game = new Game();
        $game->setName($name);
        $game->setPlatform($platform);
        $game->setDescription($description);

        $this->em->persist($game);
        $this->em->flush();
        return $game;
    }
    public function updateGame($id, $name, $platform, $description)
    {
        $game = $this->getGame($id);
        $game->setName($name);
        $game->setPlatform($platform);
        $game->setDescription($description);
        $this->em->flush();
    }
    public function removeGame($id)
    {
        $game = $this->getGame($id);
        $this->em->remove($game);
        $this->em->flush();
    }
}