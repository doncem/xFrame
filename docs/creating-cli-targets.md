All xFrame requests are available on the command line by running:

```bash
$ ./script/xframe [request]
```

You can specify a specific configuration file to use:

```bash
$ ./script/xframe index config=dev
```

And you can pass parameters:

```bash
$ ./script/xframe index key=value param1
```

If your request has a `@Parameter` annotation all single values are mapped to the values in `@Parameter`.
Key value pairs are mapped directly to the request object.
