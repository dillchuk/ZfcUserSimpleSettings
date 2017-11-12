<?php

namespace ZfcUserSimpleSettings\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use BeaucalUtil\ArrayLookup;

class SettingsServiceFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        $config = new ArrayLookup($container->get('Config'));
        return new SettingsService(
        $config->get(['zfcuser_simple_settings', 'defaults'], [])
        );
    }

}
