Plugins in xFrame allow you to easily inject an object into the Dependency Injection Container.
This allows you to gain access to your object so long as you can access the DIC.

## Setup

Edit your config file (`*.ini`) and add the following line under `[plugin]`

```ini
PLUGIN[MyPlugin]=\My\Plugin
```

Next you need to create your plugin class which needs to extend `\Xframe\Plugin\Plugin` and implement the `init` method.

```php
namespace My;

class Plugin extends \Xframe\Plugin\Plugin
{
    public function init()
    {
        // plugin init code here
    }
}
```

## Usage

You can then access an instance of your plugin in your code like this (assuming `$dic` is an instance of `\Xframe\Core\DependencyInjectionContainer`):

```php
$myPlugin = $dic->plugin['MyPlugin'];
```
