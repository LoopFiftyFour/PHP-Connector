# PHP-Connector
PHP Wrapper for Loop54 JSON API

Just include Loop54-Minified.php in your app. See http://docs.loop54.com for usage instructions.


Features:

- Wraps Loop54 JSON API with native PHP functions.
- Handles user identification using random-generated cookies. Note: Loop54_RequestHandling::getResponse() must be called before headers are sent to client for this to work.
