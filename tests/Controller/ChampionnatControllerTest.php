<?php

namespace App\Tests\Controller;

use App\Entity\Championnat;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ChampionnatControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $championnatRepository;
    private string $path = '/championnat/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->championnatRepository = $this->manager->getRepository(Championnat::class);

        foreach ($this->championnatRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Championnat index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'championnat[nom]' => 'Testing',
            'championnat[equipe]' => 'Testing',
            'championnat[sport]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->championnatRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Championnat();
        $fixture->setNom('My Title');
        $fixture->setEquipe('My Title');
        $fixture->setSport('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Championnat');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Championnat();
        $fixture->setNom('Value');
        $fixture->setEquipe('Value');
        $fixture->setSport('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'championnat[nom]' => 'Something New',
            'championnat[equipe]' => 'Something New',
            'championnat[sport]' => 'Something New',
        ]);

        self::assertResponseRedirects('/championnat/');

        $fixture = $this->championnatRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getEquipe());
        self::assertSame('Something New', $fixture[0]->getSport());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Championnat();
        $fixture->setNom('Value');
        $fixture->setEquipe('Value');
        $fixture->setSport('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/championnat/');
        self::assertSame(0, $this->championnatRepository->count([]));
    }
}
