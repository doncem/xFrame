The ACL makes it very easy for you to control access to area of your application.

## Setup

The easiest way to use the xFrame ACL classes is to write a plugin.
To do this you first need to register a plugin which will give you access to the current user, even if they are not logged in.

```ini
[plugins]
PLUGIN[user]=\Demo\Plugin\DemoUser
```

The code for this plugin class will look something like this

```php
namespace Demo\Plugin;

use Xframe\Plugin\Plugin;

class DemoUser extends Plugin
{
    public function init()
    {
        //hard coded for an example, load out of session or other storage here
        return [
            'user_id' => 1,
            'role' => 'UberAdmin',
        ];
    }
}
```

We've left out the code to load the user data and just hard-coded an array as a demonstration here as it is up to you how your store and retrieve your logged in data.
Remember to fall-back to a default user if one is not logged in though.

You don't have to return an array from the init method.
You could infact had this in a separate method call and return an object instead, this is purely a quick and dirty example.

The final step is to define your ACL.
This is done using an xFrame prefilter.

```php
namespace Demo\Request\Prefilter;

use Exception;
use Xframe\Authorisation;
use Xframe\Authorisation\Acl;
use Xframe\Core\DependencyInjectionContainer;
use Xframe\Request\Controller;
use Xframe\Request\Prefilter;
use Xframe\Request\Request;

/**
 * Implements ACL checking as a prefilter
 */
class Authoriser extends Prefilter
{
    private $acl;

    public function run(Request $request, Controller $controller)
    {
        $this->initAcl();

        $resource = $request->resource;
        $role = $this->dic->plugin->user['role'];

        if ($this->acl->isAllowed($role, $resource)) {
            return true;
        } else {
            // do some redirection here
            return false;
        }
    }

    private function initAcl()
    {
        $this->acl = new Acl();
        $this->acl->addResource('Public')
                  ->addRole('Public')
                  ->addRole('UberAdmin')
                  ->denyAll()
                  ->allow('UberAdmin', 'Public');
    }
}
```

Here you can see the `run` method simply return true or false after reading in the user role from the plugin we made earlier and checking it against the acl for the current resource.
The `initAcl` setups up the roles and resources and permissions.
Like the Zend_ACL class, xFrame ACL supports inheritance of permissions for roles and resources.

Rather than just returning false, it is advisable to perform a redirect to a 403 page.

## Usage

Using the ACL for controller actions couldn't be simpler:

```php
/**
 * @Request index
 * @Prefilter \Demo\Request\Prefilter\Authoriser
 * @CustomParam -> ["resource", "Public"]
 */
 public function action()
{ ...
```

And that's it!
