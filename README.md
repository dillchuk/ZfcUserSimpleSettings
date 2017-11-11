# ZfcUserSimpleSettings
A solid little "user settings" addition to your ZfcUser Doctrine entity; with configurable defaults.

## Installation

Install with `composer require illchuk/zfc-user-simple-settings`

Then include in your `modules.config.php`:
~~~
[..., 'ZfcUser', 'ZfcUserSimpleSettings', ...]
~~~

Finally, drop it into your User entity like the following:
~~~
class User extends ZfcEntityUser implements SettingsInterface {

    use SettingsTrait;
    // ...
}
~~~

## Configuration

Configure the default values by installing the [auto-config file](config/zfcusersimplesettings.global.php.dist).

Then add the lifecycle listener as follows:
~~~
    'doctrine' => [
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    \ZfcUserSimpleSettings\Entity\Listener\SettingsLifecycle::class,
                ],
            ],
        ],
    ],
~~~


## Bonus

This functionality can actually be applied to any Doctrine entity; ZfcUser isn't actually required.
