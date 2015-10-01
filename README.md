# PHP-Connector
PHP Wrapper for Loop54 JSON API

Just include Loop54-Minified.php in your app. See http://docs.loop54.com for usage instructions.

## Features

- Wraps Loop54 JSON API with native PHP functions.
- Handles user identification using random-generated cookies. Note: Loop54_RequestHandling::getResponse() must be called before headers are sent to client for this to work.
- Has options to handle different engine versions, from 2.2 (and below) to 2.6 (and above). See Backward compatiblity below.
- Configurable timeout (using a Loop54_Options object).
- GZIP support (can be turned off using a Loop54_Options object).
- Sends client info to engine:
  - User-Agent
  - Library version number
  - Url
  - Referer
  - User IP (uses X-Forwarded-For header if behind proxy)
  
## Backward compatibility

When implementing against older versions of Loop54 engines (or new engines which have been configured with compatibility bridges to be compatible with older API:s), custom options need to be set. To set custom options, create a Loop54_Options object and pass it to the Loop54_Request constructor.

To enable backward compatibility:

- Pre 2.6 engines: Set v25Url to true in the options object.
- Pre 2.3 engines: Set v22Collections to true in the options object.

## TODO

- Alternative method to set user Id cookies with javascript in cases where code cannot be included before headers are sent
- Support for PHP Composer with composer.json
- PHPDoc field and method documentation
