## The Basics

```php
/**
 * @Request index
 */
public function run()
{
    $this->view->var = $this->request->variable;
}
```

The request /index is mapped to the run method.
All parameters in the POST or GET will be set in `$this->request`.

Setting a variable in `$this->view` makes it available to the view.

If no view is specified the framework assumes there is a corresponding file in the `view` folder.
In this instance that file would be `view/index.html`.

## Request URI Parameter Mapping

```php
/**
 * @Request index
 * @Parameter -> ["param1"]
 * @Parameter -> ["param2", "\xframe\validator\RegEx('/u[0-9]{3}[a-z]/i')", false, 'u000A']
 */
public function run()
{
    echo $this->request->param1;
    echo $this->request->param2;
}
```

Using the `@Parameter` annotation will map parameters in the request URI to the `$this->request` object.
For example, with the URI `/index/value1/u123Z` the above code would output "value1" and "u123Z".

As seen in the above example, there are several validation settings you can include for a parameter.

1. Providing a Validator class will validate the provided parameter against a set of rules defined in the provided validators validate method.
2. Required is defaulted to true. If a parameter is options it must be defined at the end of a parameter list (think optional parameters in PHP function calls).
3. Default provides a default value to a parameter if a value is not provided.

## Setting the View Template

```php
/**
 * @Request index
 * @Template default
 */
public function run()
{
}
```

You can override the default view template using the `@Template` annotation.
In the example above instead of looking for `view/index.twig` the framework will use `view/default.twig`.

## Caching

```php
/**
 * @Request index
 * @CacheLength 3600
 */
public function run()
{
}
```

If the `CACHE_ENABLED` option is set to true and `@CacheLength` has been set the framework will store the result of the request in memcached and return it for subsequent requests.

## Prefilters

```php
/**
 * @Request index
 * @Prefilter Xframe\Request\Prefilter\ForceHTTPS
 */
public function run()
{
}
```

If the `@Prefilter` annotation is present and the value is object that implements the `Prefilter` interface the framework will call the `run()` method of the object before the request is processed.

## Custom Parameters

```php
/**
 * @Request index
 * @Prefilter Xframe\Authentication\AuthenticationPrefilter
 * @CustomParam -> ["level", "Administrator"]
 * @CustomParam -> ["userType", "Customer"]
 */
public function run()
{
    $this->request->level; // === 'Administrator'
}
```

You can define hard coded parameters using `@CustomParam`

## Overriding the Default View Type

```php
/**
 * @Request index
 * @View Xframe\View\Json
 */
public function run()
{
}
```

The `DEFAULT_VIEW` option set in the ini file can be overridden using the `@View` annotation.
