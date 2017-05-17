## Using the Dependency Injection Container

The Dependency Injection Container provides the ability to store lazy loaded objects.
By default it stores the registry, database handle (PDO), Memcache instance and Doctrine Entity Manager (if you have Doctrine installed).

### Obtaining the Registry

```php
/**
 * @Request index
 */
public function run()
{
    $this->dic->registry->get('ADMIN');
}
```

By default the Registry contains all the values stored in the configuration file (ini).

### Obtaining PDO

```php
/**
 * @Request index
 */
public function run()
{
    $results = $this->dic->database->query('SELECT * FROM `table`');
}
```

PDO is added to the dependency injection container when the `System->boot()` method is called.
The connection is not established until the database object is accessed for the first time.

## Obtaining Memcache

```php
/**
 * @Request index
 */
public function run()
{
    $results = $this->dic->cache->get('key');
}
```

If `CACHED_ENABLED` is true the dependency injection container will contain an instance of Memcache

### Setting a value in the Dependency Injection Container

To set a value in the dependency injection container you can provide the object through and anonymous inner function, so they object is load on use:

```php
/**
 * @Request index
 */
public function run()
{
    $this->dic->object = function($dic) {
        // you can use the DIC to construct your object
        $setting = $dic->get('SETTING');

        return new Object($setting);
    };
}
```

Or you can just set the value for instant loading:

```php
/**
 * @Request index
 */
public function run()
{
    $setting = $dic->get('SETTING');
    $this->dic->object = new Object($setting);
}
```
