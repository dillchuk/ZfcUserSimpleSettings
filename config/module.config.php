<?php

namespace ZfcUserSimpleSettings;

return [
    'service_manager' => [
        'factories' => [
            Entity\Listener\SettingsLifecycle::class => Entity\Listener\SettingsLifecycleFactory::class,
            Service\SettingsService::class => Service\SettingsServiceFactory::class,
        ],
    ],
];
