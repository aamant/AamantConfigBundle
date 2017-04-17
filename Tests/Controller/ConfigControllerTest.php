<?php

namespace Aamant\ConfigBundle\Tests\Controller;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class ConfigControllerTest extends WebTestCase
{
    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->service = $kernel->getContainer()->get('aamant_config.config');
        $this->generateSchema();
    }

    protected function generateSchema()
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if ( ! empty($metadata)) {
            // Create SchemaTool
            $tool = new SchemaTool($this->entityManager);
            $tool->createSchema($metadata);

        } else {
            throw new SchemaException('No Metadata Classes to process.');
        }
    }

    public function testIndex()
    {
        $this->service->set('config_name_one', 'config_value_one');
        $this->service->set('config_name_two', 'config_value_two', true);

        $client = static::createClient();

        $crawler = $client->request('GET', '/config');

        $this->assertContains('Enregistrer', $client->getResponse()->getContent());
        $this->assertContains('config_name_one', $client->getResponse()->getContent());
        $this->assertNotContains('config_name_two', $client->getResponse()->getContent());
    }
}
