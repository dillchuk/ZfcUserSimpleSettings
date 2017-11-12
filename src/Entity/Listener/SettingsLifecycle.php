<?php

namespace ZfcUserSimpleSettings\Entity\Listener;

use ZfcUserSimpleSettings\Entity\SettingsInterface;
use ZfcUserSimpleSettings\Service\SettingsService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PhpOption\Option;

class SettingsLifecycle implements EventSubscriber {

    /**
     * @var SettingsService
     */
    protected $settingsService;

    public function getSubscribedEvents() {
        return ['prePersist', 'preUpdate', 'postLoad'];
    }

    public function __construct(SettingsService $settingsService) {
        $this->settingsService = $settingsService;
    }

    /**
     * @param object $entity
     * @return Option
     */
    protected function getEntityOption($entity) {
        return Option::fromValue($entity)->filter(function($entity) {
            return ($entity instanceof SettingsInterface);
        });
    }

    public function prePersist(LifecycleEventArgs $args) {
        $this->write($args);
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $this->write($args);
    }

    protected function write(LifecycleEventArgs $args) {
        $this->getEntityOption($args->getEntity())->forAll(function($entity) {
            $this->settingsService->write($entity);
        });
    }

    public function postLoad(LifecycleEventArgs $args) {
        $this->getEntityOption($args->getEntity())->forAll(function($entity) {
            $entity->setSettingsService($this->settingsService);
            $this->settingsService->read($entity);
        });
    }

}
