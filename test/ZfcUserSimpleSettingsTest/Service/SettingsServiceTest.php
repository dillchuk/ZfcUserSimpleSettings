<?php

namespace ZfcUserSimpleSettingsTest\Service;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter as DbAdapter;
use DoctrineORMModule\Service\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ZfcUserSimpleSettings\Mock\Entity;
use ZfcUserSimpleSettings\Service\SettingsService;

class ForgotPasswordTest extends \PHPUnit_Extensions_Database_TestCase {

    /**
     * @var DbAdapter
     */
    protected $dbAdapter;

    /**
     * @var TableGateway
     */
    protected $gateway;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var SettingsService
     */
    protected $settingsService;

    public function setUp() {
        parent::setUp();

        $this->gateway = new TableGateway(
        'entity', $this->getAdapter()
        );

        $doctrineConfig = include __DIR__ . '/../../config.php';
        $serviceManager = new ServiceManager($doctrineConfig['service_manager']);
        $serviceManager->setService('Configuration', $doctrineConfig);

        $emFactory = new EntityManagerFactory('orm_default');
        $this->entityManager = $emFactory($serviceManager, EntityManager::class);

        $annotations = __DIR__ . '/../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php';
        AnnotationRegistry::registerFile($annotations);

        $this->settingsService = $serviceManager->get(SettingsService::class);
    }

    protected function getAdapter() {
        if ($this->dbAdapter) {
            return $this->dbAdapter;
        }
        $config = include __DIR__ . '/../../dbadapter.php';
        $config = $config['db'];
        $config['driver'] = 'PDO';
        $this->dbAdapter = new DbAdapter($config);
        return $this->dbAdapter;
    }

    protected function getConnection() {
        return $this->createDefaultDBConnection($this->getAdapter()->getDriver()->getConnection()->getResource());
    }

    protected function getDataSet() {
        return $this->createFlatXMLDataSet(__DIR__ . '/data/entity-seed.xml');
    }

    public function testSetup() {
        $this->assertNotEmpty($this->gateway->select(['id' => 1]));
        $this->assertNotEmpty($this->gateway->select(['id' => 2]));
        $this->assertNotEmpty($this->gateway->select(['id' => 3]));
        $this->assertNotEmpty($this->gateway->select(['id' => 4]));
        $this->assertNotEmpty($this->gateway->select(['id' => 5]));
    }

    /**
     * @dataProvider dataRead
     */
    public function testRead($id, $setting, $expected) {
        $entity = $this->entityManager->find(Entity::class, $id);
        $this->assertEquals($expected, $entity->getSetting($setting));
    }

    static public function dataRead() {
        return [
            [1, 'setting', null],
            [2, 'setting', null],
            [3, 'setting', null],
            [4, 'setting', null],
            [5, 'setting', 'yes!'],
        ];
    }

    public function testEntityUpdates() {

        $entities = [
            $this->entityManager->find(Entity::class, 1),
            new Entity,
        ];
        $entities[1]->setSettingsService($this->settingsService);

        foreach ($entities as $entity) {
            $this->assertCount(2, $entity->getSettingsList());
            $this->assertEquals('one', $entity->getSetting('default1'));
            $this->assertEquals('two', $entity->getSetting('default2'));

            $entity->setSetting('settingA', 'asdf');
            $this->assertCount(3, $entity->getSettingsList());
            $this->assertEquals('asdf', $entity->getSetting('settingA'));

            $entity->setSetting('settingA', 'qwer');
            $this->assertCount(3, $entity->getSettingsList());
            $this->assertEquals('qwer', $entity->getSetting('settingA'));

            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);
            $this->entityManager->refresh($entity);
            $this->assertEquals('qwer', $entity->getSetting('settingA'));

            $entityAgain = $this->entityManager->find(
            Entity::class, $entity->getId()
            );
            $this->assertEquals('qwer', $entityAgain->getSetting('settingA'));

            $entity->deleteSetting('settingA');
            $this->assertNull($entity->getSetting('settingA'));
        }
    }

}
