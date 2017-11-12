<?php

namespace ZfcUserSimpleSettings\Entity;

use ZfcUserSimpleSettings\Service\SettingsService;

interface SettingsInterface {

    public function setSettingsService(SettingsService $settingsService);

    public function getSettings();

    public function setSettings($settings);

    public function setSetting($setting, $value);

    public function getSetting($setting);

    public function getSettingsList();

    public function deleteSetting($setting);
}
