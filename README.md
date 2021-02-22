# Lagoon PHP SDK

The *Lagoon SDK for PHP* makes it easy for developers to connect their applications to the Lagoon GraphQL service in PHP Code.

## Getting started

Require the package using [Composer](https://getcomposer.org/):

```
composer require uselagoon/lagoon-php-sdk
```

## Quick Examples

### Fetch all projects

See [`tests/lagoon-php-sdk-test`](tests/lagoon-php-sdk-test) for a working example. This script is [tested on GitHub](https://github.com/uselagoon/lagoon-php-sdk/actions).

```php
<?php

require 'vendor/autoload.php';

use Lagoon\LagoonClient;

// The container exposes port 3000 on the host by default.
$endpoint = "http://localhost:3000/graphql";

// The development container uses this token for everyone.
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2xlIjoiYWRtaW4iLCJpc3MiOiJhcGktZGF0YS13YXRjaGVyLXB1c2hlciIsImF1ZCI6ImFwaS5kZXYiLCJzdWIiOiJhcGktZGF0YS13YXRjaGVyLXB1c2hlciJ9.GiSJpvNXF2Yj9IXVCsp7KrxVp8N2gcp7-6qpyNOakVw";

try {
  $client = new LagoonClient($endpoint, $token);
  $response = $client->project()->all()->execute();

  if ($response->hasErrors()) {
    throw new \Exception("There were errors returned from the GraphQL API: " . implode(PHP_EOL, $response->errors()));
  }
  else {
    $projects = $response->all();
    print "Projects Found: " . count($projects);
  }
} catch (\Exception $e) {
   print "ERROR: " . $e->getMessage();
   exit(1);
}
```

### Fetch all project names

``` php
<?php

use Lagoon\LagoonClient;

$client = new LagoonClient($endpoint, $token);
$projects = $client->project()->all()->fields(['name'])->execute();
```

### Add a project

``` php
<?php

use Lagoon\LagoonClient;

$client = new LagoonClient($endpoint, $token);
$project = [
  'name' => $name,
  'gitUrl' => $gitUrl,
  'openshift' => 2,
  'productionEnvironment' => 'master',
  'branches' => 'true',
];
$response = $client->project()->add($project)->execute();
```

## Developing this Package

This package interacts with the Lagoon Container Hosting System's API.

You need to have a running Lagoon API instance to develop and test against.

This package includes the Lagoon codebase using the `require-dev` section of composer.json in the [`/tests`](./tests) folder.

### Launch Lagoon API Instance.

Run the following commands to download Lagoon source to the `vendor/uselagoon/lagoon` folder.

        # Composer install with --dev dependencies.  (default behavior)
        composer install

        # Composer install without --dev dependencies. 
        # Do this to build and release your codebase to production.
        composer install --no-dev

        # Run bin/api-* scripts to start and test the API containers.
        bin/api-start
        bin/api-test

### Tests

The `bin/api-test` script will launch the containers and wait until the API container is available at https://localhost:3000.

The `api-start` script is simply a wrapper for the `make api-development` command inside `uselagoon/lagoon`. This is the same command used by Lagoon developers to work on the API, so a full development environment is available.

## About this Package


This project was originally developed by @steveworley et al in the repo https://github.com/steveworley/lagoon-php-sdk.
It is currently being improved upon to be released as officially supported by the Lagoon Team. 
