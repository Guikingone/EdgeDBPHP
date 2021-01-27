<h1 align="center">EdgeDB - PHP client</h1>

<p align="center">A EdgeDB client written in PHP</p>

## Main features

- EdgeQL over HTTP
- GraphQL over HTTP

## Installation

```bash
$ composer require guikingone/edgedb-php-client
```

## Usage

```php
<?php

use EdgeDB\Client;

$client = Client::connect('edgedb://edgedb@localhost/foo');

# ...
```

## Documentation

The full documentation can be found [here](doc).
