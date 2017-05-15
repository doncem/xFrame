Note: Doctrine2 must be installed.

## Application Access

The Doctrine2 Entity Manager is provided through the dependency injection container:

```php
/**
 * @Request index
 */
public function run()
{
    $query = $this->dic->em->createQuery('SELECT * FROM Xframe\Demo\Model\User');
}
```

It is not loaded until first use and it uses the database settings in the configuration file.

## CLI Access

If you cd to the root directory of your project the `doctrine` cli program will detect the cli-config.php bootstrap and use the xFrame Entity Manager instance.

```bash
$ cd /var/www/example.org
$ doctrine orm:schema-tool:create
```

## More on Doctrine2

[Doctrine2 Documentation](http://doctrine-project.org)
