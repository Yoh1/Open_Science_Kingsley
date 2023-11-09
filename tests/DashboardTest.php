<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class DashboardTest extends WebTestCase
{
    public function testCreatePublicationWithUpload(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/dashboard/create');
        $this->assertSelectorTextContains('h1', 'Cree une publication');

       $path= realpath('./tests/pdf/BetterScan-Pitch-Deck-PreSeed-2-654b650fc024f.pdf');

        $submitButton = $crawler->selectButton('create');
        $form = $submitButton->form();
        $form['publication[title]'] = 'KingsleyKingsley';
        $form['publication[upload]']->upload($path);
        $client->submit($form);
        $client->followRedirect();

       // $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);


    }
}