<?php

namespace App\Tests\Controller;

use App\Entity\EpreuveCompetition;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EpreuveCompetitionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $epreuveCompetitionRepository;
    private string $path = '/epreuve/competition/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->epreuveCompetitionRepository = $this->manager->getRepository(EpreuveCompetition::class);

        foreach ($this->epreuveCompetitionRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('EpreuveCompetition index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'epreuve_competition[competition]' => 'Testing',
            'epreuve_competition[epreuve]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->epreuveCompetitionRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new EpreuveCompetition();
        $fixture->setCompetition('My Title');
        $fixture->setEpreuve('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('EpreuveCompetition');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new EpreuveCompetition();
        $fixture->setCompetition('Value');
        $fixture->setEpreuve('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'epreuve_competition[competition]' => 'Something New',
            'epreuve_competition[epreuve]' => 'Something New',
        ]);

        self::assertResponseRedirects('/epreuve/competition/');

        $fixture = $this->epreuveCompetitionRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getCompetition());
        self::assertSame('Something New', $fixture[0]->getEpreuve());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new EpreuveCompetition();
        $fixture->setCompetition('Value');
        $fixture->setEpreuve('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/epreuve/competition/');
        self::assertSame(0, $this->epreuveCompetitionRepository->count([]));
    }
}
