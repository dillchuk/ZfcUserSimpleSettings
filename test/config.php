<?php

return [
    'doctrine' => [
        'cache' => [
            'array' => [
                'class' => Doctrine\Common\Cache\ArrayCache::class,
                'namespace' => 'DoctrineModule',
            ],
        ],
        'connection' => [
            'orm_default' => [
                'configuration' => 'orm_default',
                'eventmanager' => 'orm_default',
                'params' => [
                    'user' => 'travis',
                    'password' => '',
                    'url' => 'pdo-mysql://travis@localhost/zfcusersimplesettings?charset=utf8',
                ],
                'driverClass' => 'PDOMySqlDriver',
            ],
        ],
        'configuration' => [
            'orm_default' => [],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'ZfcUserSimpleSettings\\Mock' => 'ZfcUserSimpleSettings_driver',
                ],
            ],
            'ZfcUserSimpleSettings_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'paths' => __DIR__ . '/../src/Mock',
            ],
        ],
        'entitymanager' => [
            'orm_default' => [
                'connection' => 'orm_default',
                'configuration' => 'orm_default',
            ],
        ],
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    \ZfcUserSimpleSettings\Entity\Listener\SettingsLifecycle::class,
                ],
            ],
        ],
        'entity_resolver' => [
            'orm_default' => [],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \ZfcUserSimpleSettings\Service\SettingsService::class => \ZfcUserSimpleSettings\Service\SettingsServiceFactory::class,
            \ZfcUserSimpleSettings\Entity\Listener\SettingsLifecycle::class => \ZfcUserSimpleSettings\Entity\Listener\SettingsLifecycleFactory::class,
        ],
        'abstract_factories' => [
            'DoctrineModule' => \DoctrineModule\ServiceFactory\AbstractDoctrineServiceFactory::class,
        ],
        'aliases' => [
            'config' => 'Configuration',
            'Config' => 'Configuration',
        ],
    ],
    'doctrine_factories' => [
        'connection' => \DoctrineORMModule\Service\DBALConnectionFactory::class,
        'configuration' => \DoctrineORMModule\Service\ConfigurationFactory::class,
        'entitymanager' => \DoctrineORMModule\Service\EntityManagerFactory::class,
        'entity_resolver' => \DoctrineORMModule\Service\EntityResolverFactory::class,
        'cache' => \DoctrineModule\Service\CacheFactory::class,
        'eventmanager' => \DoctrineModule\Service\EventManagerFactory::class,
        'driver' => \DoctrineModule\Service\DriverFactory::class,
    ],
    'zfcuser_simple_settings' => [
        'defaults' => [
            \ZfcUserSimpleSettings\Mock\Entity::class => [
                'default1' => 'one',
                'default2' => 'two',
            ],
        ],
    ],
];
