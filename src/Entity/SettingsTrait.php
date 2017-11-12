<?php

namespace ZfcUserSimpleSettings\Entity;

use Doctrine\ORM\Mapping as ORM;
use ZfcUserSimpleSettings\Service\SettingsService;
use Webmozart\Assert\Assert;

trait SettingsTrait {

    /**
     * @ORM\Column(type="text")
     */
    protected $settings;

    /**
     * @var SettingsService
     */
    protected $settingsService;

    public function setSettingsService(SettingsService $settingsService) {
        $this->settingsService = $settingsService;
    }

    public function setSetting($setting, $value) {
        Assert::notNull($this->settingsService, 'SettingsService was not set');
        $this->settingsService->setSetting($this, $setting, $value);
    }

    public function getSetting($setting) {
        Assert::notNull($this->settingsService, 'SettingsService was not set');
        return $this->settingsService->getSetting($this, $setting);
    }

    public function getSettingsList() {
        Assert::notNull($this->settingsService, 'SettingsService was not set');
        return $this->settingsService->getSettingsList($this);
    }

    public function deleteSetting($setting) {
        Assert::notNull($this->settingsService, 'SettingsService was not set');
        return $this->settingsService->deleteSetting($this, $setting);
    }

    public function getSettings() {
        return $this->settings;
    }

    public function setSettings($settings) {
        $this->settings = $settings;
    }

}
