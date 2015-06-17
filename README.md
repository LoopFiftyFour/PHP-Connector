# PHP-Connector
PHP Wrapper for Loop54 JSON API

Just include Loop54-Minified.php in your app. See http://docs.loop54.com for usage instructions.


## Features:

- Wraps Loop54 JSON API with native PHP functions.
- Handles user identification using random-generated cookies. Note: Loop54_RequestHandling::getResponse() must be called before headers are sent to client for this to work.
- Has options to handle different engine versions, from 2.2 (and below) to 2.6 (and above)

## TODO:

- Alternative method to set user Id cookies with javascript in cases where code cannot be included before headers are sent
- Send more user variables to engine:
  - User-Agent
  - Library version number
  - Url
  - Referer
  - X-Forwarded-For header (if behind proxy)
- Support for more config:
  - Request timeout
- GZIP support
