<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GamesControllerTest extends WebTestCase
{
    public function testGames()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/games');
    }

    public function testAddgames()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addGames');
    }

}
