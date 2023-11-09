<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class DashboardCreateTest extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/dashboard/create');
        $pdfFile = "./pdf/17515900000484777152589-654a591367e7b.pdf";
        $submitButton = $crawler->selectButton('create');
        $form = $submitButton->form();
        $form['publication[title]'] = 'test';
        $form['publication[upload]']->upload($pdfFile);
        $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert-success', 'Le fichier a été uploadé avec succès.');


    }
}
