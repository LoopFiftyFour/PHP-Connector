# PHP-Connector
PHP Wrapper for Loop54 JSON API

Just include Loop54-Minified.php in your app. See http://docs.loop54.com for usage instructions.

## Features

- Wraps Loop54 JSON API with native PHP functions.
- Handles user identification using random-generated cookies. Note: Loop54_RequestHandling::getResponse() must be called before headers are sent to client for this to work.
- Configurable timeout (using a Loop54_Options object).
- GZIP support (can be turned off using a Loop54_Options object).
- Sends client info to engine:
  - User-Agent
  - Library version number
  - Url
  - Referer
  - User IP (uses X-Forwarded-For header if behind proxy)
- Expects valid UTF-8

## Backward compatibility

Loop54 engines are (as of 3.1) able to use different API versions depending on the version of the library that connects to it. Contact your technical administrator at Loop54 to make sure that your engine is correctly configured.
  
## TODO

- Alternative method to set user Id cookies with javascript in cases where code cannot be included before headers are sent
- Support for PHP Composer with composer.json
- PHPDoc field and method documentation
