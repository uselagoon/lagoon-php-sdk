# Lagoon PHP SDK

The *Lagoon SDK for PHP* makes it easy for developers to connect their applications to the Lagoon GraphQL service in PHP Code.

## Getting started

Require the package using [Composer](https://getcomposer.org/):

```
composer require uselagoon/lagoon-php-sdk
```

## Quick Examples

### Fetch all projects

```php
<?php

use Lagoon\LagoonClient;
use Lagoon\LagoonResponse;

// The full URL to the GraphQL endpoint.
$endpoint = "https://lagoon.api:8000/graphql";

// The Token to use to connect to the LagoonAPI. 
$token = "APITokenFromLagoonAPI";

$client = new LagoonClient($endpoint, $token);

/** @var LagoonResponse $projects */
$response = $client->project()->all()->execute();

if ($response->hasErrors()) {
  throw new \Exception("There were errors returned from the GraphQL API: " . implode(PHP_EOL, $response->errors()));
}
else {
  $projects = $response->all();
  print "Projects Found: " . count($projects);
  print_r($projects);
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
  'name' => 'my-proejct',
  'customer' => 1,
  'openshift' => 1,
  'gitUrl' => 'git@github.com:test/test.git'
  'productEnvironment' => 'master',
  'branches' => 'master',
];
$response = $client->project()->add($project)->execute();
```

## Developing this Package

This package interacts with the Lagoon Container Hosting System's API.

You need to have a running Lagoon API instance to develop and test against.

### Launch Lagoon API Instance.

To launch a development environment that includes this package and Lagoon API Containers, just use `composer`:

        # Composer install with --dev dependencies. 
        composer install

        # Run bin/api-* scripts to start and test the API containers.
        bin/api-start
        bin/api-test

The `bin/api-test` script will keep running until the API container is available at https://localhost:3000.

The `api-start` script is simply a wrapper for the `make api-development` command inside `uselagoon/lagoon`. This is the same command used by Lagoon developers to work on the API, so a full development environment is available.

### Use the Lagoon API Instance.

Once the containers are running, set the default `$endpoint` and `$token` for the development containers.

See [vendor/uselagoon/lagoon/docker-compose.yaml](vendor/uselagoon/lagoon/docker-compose.yaml)

        // The container exposes port 3000 on the host by default. 
        $endpoint = "https://localhost:3000/graphql";

        // The development container uses this token for everyone. 
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2xlIjoiYWRtaW4iLCJpc3MiOiJhcGktZGF0YS13YXRjaGVyLXB1c2hlciIsImF1ZCI6ImFwaS5kZXYiLCJzdWIiOiJhcGktZGF0YS13YXRjaGVyLXB1c2hlciJ9.GiSJpvNXF2Yj9IXVCsp7KrxVp8N2gcp7-6qpyNOakVw";
        
        $client = new LagoonClient($endpoint, $token);
        $projects = $client->project()->all()->execute()->all();

        print_r($projects);


## About this Package


This project was originally developed by @steveworley et al in the repo https://github.com/steveworley/lagoon-php-sdk.
It is currently being improved upon to be released as officially supported by the Lagoon Team. 
