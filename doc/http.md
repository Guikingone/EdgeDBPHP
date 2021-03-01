# HTTP

This library provides HTTP clients to interact with both EdgeQL and GraphQL endpoints.

- [EdgeQL](#edgeql)
- [GraphQL](#graphql)

Extra features:  

- [Cache](#cache)

## EdgeQL

EdgeQL is the primary choice when it comes to interacting with EdgeDB using HTTP,
once [enabled](https://www.edgedb.com/docs/clients/90_edgeql/index) and as explained 
in the [documentation](https://www.edgedb.com/docs/clients/90_edgeql/protocol),
both `GET` and `POST` methods are available.

### GET

```php
<?php

declare(strict_types=1);

use EdgeDB\EdgeQLHttpClient;

$client = new EdgeQLHttpClient('http://127.0.0.1:<instance-port>/db/<database-name>/edgeql');
$result = $client->get("SELECT User FILTER User.name = 'John';");
```

### POST

```php
<?php

declare(strict_types=1);

use EdgeDB\EdgeQLHttpClient;

$client = new EdgeQLHttpClient('http://127.0.0.1:<instance-port>/db/<database-name>/edgeql');
$result = $client->post("SELECT User FILTER User.name = 'John';");
```

// TO DOCUMENT

## GraphQL

GraphQL is the secondary choice when it comes to interacting with EdgeDB using HTTP,
once [enabled](https://www.edgedb.com/docs/clients/99_graphql/index) and as explained
in the [documentation](https://www.edgedb.com/docs/clients/99_graphql/protocol),
both `GET` and `POST` methods are available.

### GET

```php
<?php

declare(strict_types=1);

use EdgeDB\GraphQLHttpClient;

$client = new GraphQLHttpClient('http://127.0.0.1:<instance-port>/db/<database-name>/edgeql');
$result = $client->get("SELECT User FILTER User.name = 'John';");
```

## Cache
