<?php

namespace ZfcUserSimpleSettings\Service;

use ZfcUserSimpleSettings\Entity\SettingsInterface;
use Zend\Stdlib\Parameters;
use Webmozart\Assert\Assert;

class SettingsService {

    /**
     * @var array of Parameters, per entity instance
     */
    protected $settings = [];

    /**
     * @var array of Parameters, per entity class
     */
    protected $defaults = [];

    /**
     * @param array $classDefaults array[::class] => [map of scalar values]
     */
    public function __construct($classDefaults = []) {
        foreach ($classDefaults as $class => $defaults) {
            $this->defaults[$class] = new Parameters($this->filterScalar($defaults));
        }
    }

    public function getSetting(SettingsInterface $entity, $setting) {
        $defaults = $this->getDefaults($entity);
        $settings = $this->getSettingsReference($entity);
        return $settings->get($setting, $defaults->offsetGet($setting));
    }

    /**
     * User-specific settings + defaults.
     *
     * @return array
     */
    public function getSettingsList(SettingsInterface $entity) {
        $settings = $this->getSettingsReference($entity);
        return (array) $settings + (array) $this->getDefaults($entity);
    }

    public function setSetting(SettingsInterface $entity, $setting, $value) {
        Assert::scalar($value);
        $settings = $this->getSettingsReference($entity);
        $settings->offsetSet($setting, (string) $value);
        $this->write($entity);
    }

    public function deleteSetting(SettingsInterface $entity, $setting) {
        $settings = $this->getSettingsReference($entity);
        $settings->offsetUnset($setting);
        $this->write($entity);
    }

    public function read(SettingsInterface $entity) {
        $input = json_decode($entity->getSettings(), true);
        $input = is_array($input) ? $input : [];
        $settings = $this->getSettingsReference($entity);
        $settings->exchangeArray($this->filterScalar($input));
    }

    public function write(SettingsInterface $entity) {
        $settings = $this->filterScalar(
        (array) $this->getSettingsReference($entity)
        );
        $entity->setSettings(json_encode($settings));
    }

    /**
     * @return Parameters
     */
    protected function &getSettingsReference(SettingsInterface $entity) {
        $hash = spl_object_hash($entity);
        if (!isset($this->settings[$hash])) {
            $this->settings[$hash] = new Parameters;
        }
        return $this->settings[$hash];
    }

    /**
     * @return Parameters
     */
    protected function getDefaults(SettingsInterface $entity) {
        $class = get_class($entity);
        if (!isset($this->defaults[$class])) {
            $this->defaults[$class] = new Parameters;
        }
        return $this->defaults[$class];
    }

    /**
     * @return array
     */
    protected function filterScalar(array $scalars) {
        return array_filter($scalars,
        function($value) {
            return is_scalar($value);
        });
    }

}
