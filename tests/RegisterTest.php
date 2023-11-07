<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class RegisterTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Formulaire d'inscription");

        $submitButton = $crawler->selectButton("subscribe");
        $form = $submitButton->form();
        $form["registration_form[name]"] = "John";
        $form["registration_form[fname]"] = "Doe";
        $form["registration_form[email]"] = "JohnDoe@gmail.com";
        $form["registration_form[plainPassword]"] = 123456;

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
