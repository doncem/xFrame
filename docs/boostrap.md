It may be necessary to bootstrap your application with certain settings.
This can achieved in two ways:

## Controller Level Bootstrap

Using a shared controller:

```php
use \Xframe\Request\Controller;

class MyController extends Controller
{
    /**
     * Called before run() and about()
     */
    public function init()
    {
        $this->view->navigation = ['home', 'about', 'contact'];
    }

    /**
     * @Request index
     */
    public function run()
    {
    }

    /**
     * @Request about
     */
    public function about()
    {
    }
}
```

## Application Level Bootstrap

Using inheritance:

```php
use \Xframe\Request\Controller;

class Bootstrap extends Controller
{
    /**
     * Called all requests
     */
    public function init()
    {
        $this->view->navigation = ['home', 'about', 'contact'];
    }
}

class Index extends Bootstrap
{
    /**
     * @Request index
     */
    public function run()
    {
    }
}

class About extends Bootstrap
{
    /**
     * @Request about
     */
    public function about()
    {
    }
}
```
