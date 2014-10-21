<?php

namespace BV\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContact()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contact');
    }

    public function testContactpost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contactPost');
    }

}
