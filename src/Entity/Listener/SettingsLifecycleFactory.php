<?php

namespace ZfcUserSimpleSettings\Entity\Listener;

use ZfcUserSimpleSettings\Service\SettingsService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class SettingsLifecycleFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        return new SettingsLifecycle($container->get(SettingsService::class));
    }

}
