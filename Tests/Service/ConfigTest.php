<?php
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */
class ConfigTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Aamant\ConfigBundle\Service\Config
     */
    private $service;

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

    public function testGetSet()
    {
        $this->service->set('test', 'test1');

        $entity = $this->entityManager->getRepository('AamantConfigBundle:Config')
            ->findOneByName('test');

        $this->assertInstanceOf('\Aamant\ConfigBundle\Entity\Config', $entity);
        $this->assertEquals('test1', $entity->getValue());

        $entity->setValue('test2');
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->assertEquals('test2', $this->service->get('test'));
        $this->service->set('test', 'ok');
        $this->assertEquals('ok', $this->service->get('test'));
    }
}