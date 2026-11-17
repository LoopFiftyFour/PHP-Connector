# Upgrade guide

## From 4.x to 5.0

### Requests and responses moved to their own namespaces

| Before (4.x)                                              | Now (5.0)                                  |
|-----------------------------------------------------------|--------------------------------------------|
| `Loop54\API\SearchRequest`, `Loop54\API\Request`, …       | `Loop54\API\Request\…`                     |
| `Loop54\API\ResultsOptions`, `FilterParameter`, `RequestAliasFields` | `Loop54\API\Request\Parameters\…` |
| `Loop54\API\SearchResponse`, `Loop54\API\Response`, …     | `Loop54\API\Response\…`                    |

The old names still work as aliases, including in type declarations and
`instanceof` checks, but are deprecated and will be removed in a future major
version. Updating your `use` statements is all that's needed.

### Parameter and return types

All requests, responses, parameter classes and `Client` now declare parameter
and return types. Calls that passed the documented types keep working. If your
code uses `declare(strict_types=1)`, passing e.g. a numeric string where an
`int` is expected (`->take('5')`) now throws a `TypeError` instead of being
converted silently.

Parameter names are unchanged, so calls with named arguments keep working.

### Request and response classes are `final`

They can no longer be extended. For custom calls, use composition rather than
inheritance, e.g. via factory methods. In case you have any issues, please feel free
to open a Github issue and explain your use-case.

### `Client::doCustomRequest()` returns a `CustomResponse`

It used to return the raw engine result. That result is now available via
`->getRaw()`:

```php
// Before
$result = $connector->doCustomRequest('name', $data);
// Now
$result = $connector->doCustomRequest('name', $data)->getRaw();
```

### `Client::queryOther()` is deprecated

`Client::query()` now handles every request, including custom requests. Replace
`queryOther($request)` with `query($request)`.

### `Request::perform()` changed

`perform()` now takes a single `Loop54\API\Request\RequestContext` instead of
eight separate arguments, and is marked `@internal`. It is called by
`Client::query()`; if your code called it directly, call `query()` instead.
