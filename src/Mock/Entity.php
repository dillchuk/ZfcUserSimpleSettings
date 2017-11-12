<?php

namespace ZfcUserSimpleSettings\Mock;

use Doctrine\ORM\Mapping as ORM;
use ZfcUserSimpleSettings\Entity\SettingsInterface;
use ZfcUserSimpleSettings\Entity\SettingsTrait;
use Zend\Hydrator\Filter\FilterProviderInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="entity")
 */
class Entity implements SettingsInterface {

    use SettingsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    public function getId() {
        return $this->id;
    }

}
