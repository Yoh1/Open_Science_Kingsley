<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');

        $submitButton = $crawler->selectButton('Connexion');
        $form = $submitButton->form();
        $form['_username'] = "JohnDoe@gmail.com";
        $form["_password"] = 123456;

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);




    }
}
