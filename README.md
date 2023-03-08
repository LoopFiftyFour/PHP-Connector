Loop54 API: PHP Connector
=========================

Loop54 is a learning search engine for e-commerce. This library aims to make it
as easy as possible to integrate the service with your PHP site.


Installation
------------

We recommend using [Composer](https://getcomposer.org/) to handle your
dependencies; if you are, install the Loop54 PHP connector by running

```sh
$ composer require loop54/php-connector
```

This will install it to the `vendor` subdirectory used by composer for
third-party dependencies. Composer also sets up automatic autoloads of all the
dependencies it manages, but to tell your PHP about these, you may have to add
the following call somewhere early in your application.

```php
require_once(__DIR__ . '/vendor/autoload.php');
```

### Using PHAR Archive

There are also standalone phar archives of all releases available for download. They are not automatically kept up to
date, so if you need one of these, please contact us.

Usage
-----

This connector is split into a simple, high-level library, and an advanced,
low-level API binding. You find the high-level library in the `lib` directory,
and the low-level binding in the `lib/OpenAPI` directory.

For the most common tasks, the high-level library should suffice. This includes
things such as

- Making search requests
- Adding facets
- Listing products in a category
- Filtering out e.g. products that are out of stock
- Sorting products based on their price
- Paginating among results
- Getting results related to a search query
- Getting autocomplete suggestions
- Sending user interaction events
- Giving the user spelling suggestions

However, some more advanced functionality is only available using the low-level
binding. Examples of this includes:

- Sorting on more than one attribute simultaneously (e.g. primarily on
  manufacturer, but then within the same manufacturer, on increasing price)
- Customise the order of facets returned by the engine
- Getting the total number of hits for a search query
- Submitting arbitrary data for client-specific purposes
- Showing all products that match complicated criteria

The high-level library is implemented in terms of the low-level one, and is
designed to be fully interoperable with it. This means that it is possible (and
encouraged!) to use the high-level connector for most operations, and then drop
down to the lower level binding only briefly for operations that require it.


### Configuration

Most of the interaction you have with the connector starts out from an instance
of the `\Loop54\API\Client` class. The most common way to instantiate this for a
web application is

```php
$remoteClientInfo = new \Loop54\API\RemoteClientInfo\WebClient();
$connector = new \Loop54\API\Client(
    'URL_TO_YOUR_ENDPOINT',
    'YOUR_API_KEY',
    $remoteClientInfo
);
```

In order for the Loop54 engine to properly register user interaction, it needs
to be told about things like the IP address, user ID, and user-agent of the end
user. The `\Loop54\API\RemoteClientInfo\WebClient` object will automatically get
this information from the PHP environment.

If you want more control over this, the `\Loop54\API\RemoteClientInfo\Client`
interface contains the methods to implement. A sample implementation that simply
returns static values is available as
`\Loop54\API\RemoteClientInfo\SimpleClient`.


### Implementation Guidance

After you have configured the connector, you are good to go! The next thing to
read is the [Loop54 technical documentation and developer
guide](https://docs.loop54.com/latest/), which contains general implementation
guidance and advice – with code examples for this PHP connector.


### Examples

Included are two examples of how to use the library.

#### `examples/Simple.php`

A plain console application focused on demonstrating the core features of the
connector, and not dealing with things like routes, templates, error handling
and other real-world concerns.

#### `examples/app`

A full-blown Symfony web applications with some features implemented, in order
to show what using the connector may look like in a more realistic scenario.



### High-level library

The best available assistance for dealing with the high-level library is found
under *Implementation Guidance* above.



### Low-level Bindings

Since the low-level binding is practically a 1:1 mapping of [the JSON API the
engine uses to communicate](https://docs.loop54.com/latest/api/docs.html),
familiarity with this API helps in navigating the low-level binding.

The low-level binding is generated from the API specification using the [OpenAPI
generator](https://github.com/OpenAPITools/openapi-generator). The classes
produced by the generator are extremely regular in their design, so once you
have learned to translate the API schema to a generated PHP class, you will be
able to work with them all fluently.



Contributing
------------

We welcome any and all contributions! None of us are PHP experts, so if you
think you've find something that looks wrong – it probably is. Feel free to
​file an issue or submit a pull request.

### Setup

First make sure you've installed all dependencies, including dev ones:

```
$ composer install
```

### Running the Symphony app

The library has a test application using the Symphony web framework. This
can be used to verify and test functionality while developing. The app can
be run on a local webserver on port 5001 by

```
$ cd examples/app
$ php -S localhost:5001
```

Choose whatever port you like. Then open localhost:5001 (or whatever you 
chose) in a web browser and you can try out the app. It's very simple, 
but supports search, autoComplete and createEvents.

### Running the examples

The code under /examples are runnable and act as one part of testing the 
library. They can be run using

```
$ php examples/Simple.php
```

These are set up to perform a set of requests to 
https://helloworld.54proxy.com/ and should not throw any exceptions.

### Testing

Unit and integration tests can be run by

```
./vendor/bin/phpunit test
```

To test multiple PHP versions (using docker) run

```
./test-matrix.sh
```

This will run the (examples)(### Running the examples) in addition to the unit and integration tests.

### Generating Low-level Library

Code under `lib/OpenAPI` is generated from [the OpenAPI
specification](https://docs.loop54.com/latest/api/docs.html) with a semi-manual
process. First, download the schema from https://docs.loop54.com/latest/api/schema.json. 
Then, we generate the code from the specification and write it to a
temporary location.

    openapi-generator-cli generate \
        -g php -i schema.json -o ~/tmp/phpgen \
        --invoker-package 'Loop54\API\OpenAPI'
		
If you don't have openapi-generator-cli you can get it with

	npm install @openapitools/openapi-generator-cli -g

**If you are having problem with the command above generating names without
back-slashes (eg 'Loop54APIOpenAPI'). Try double escaping the command:
`--invoker-package 'Loop54\\API\\OpenAPI'` (or even `\\\\`)**

this generates also a bunch of supporting code that we're not very interested
in, so we copy over just the interesting bits to the repository. (Note that the
periods are required here, in order for rsync to correctly guess our intent.)

    rsync -a ~/tmp/phpgen/lib/. lib/OpenAPI/.

However! At least as of `openapi-generator` 3.3.1, the generated code does not
run out of the box: the `ObjectSerializer` class is broken for model classes
that are in a different namespace. Fortunately, the fix is simple:

    --- lib/OpenAPI/ObjectSerializer.php	2018-10-26 15:39:46.000000000 +0200
    +++ lib/OpenAPI/ObjectSerializer.php	2018-10-26 15:57:45.000000000 +0200
    @@ -301,6 +301,9 @@
                 }
                 return $data;
             } else {
    +            if ($class[0] != '\\') {
    +                $class = '\Loop54\API\OpenAPI\Model\\' . $class;
    +            }
                 $data = is_string($data) ? json_decode($data) : $data;
                 // If a discriminator is defined and points to a valid subclass, use it.
                 $discriminator = $class::DISCRIMINATOR;

Either patch this manually (it's a three-liner after all) or save the diff as
`serializerfix.diff` and then you can apply it by running

```sh
patch < serializerfix.diff
```

There is probably a better way to do this, and if you figure one out, please
submit a patch!

### Building phar Archive

Using [phar-composer](https://github.com/clue/phar-composer):

 1. First, ensure development requirements are cleaned out, and only runtime
    dependencies are installed. Without this step, the archive would include
    e.g. the example Symfony app, which just occupies a bunch of space for no
    good reason.

    ```sh
    $ composer install --no-dev
    ```

 2. Then create a phar archive from the connector source and all installed
    dependencies.

    ```sh
    $ php -d phar.readonly=off phar-composer.phar build .
    Loop54PHPConnector-1.0.0.phar
    ```
