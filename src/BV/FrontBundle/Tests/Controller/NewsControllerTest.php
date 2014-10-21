<?php

namespace BV\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewsControllerTest extends WebTestCase
{
    public function testDisplayactivenews()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/displayActiveNews');
    }

}
