<?php

namespace Lagoon;

use EUAutomation\GraphQL\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * The LagoonClient.
 */
class LagoonClient implements LagoonClientInterface {

  /**
   * The graphql client.
   *
   * @var EUAutomation\GraphQL\Client
   */
  protected $client;

  /**
   * The default token used for development instances of the Lagoon API.
   */
  const defaultToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2xlIjoiYWRtaW4iLCJpc3MiOiJhcGktZGF0YS13YXRjaGVyLXB1c2hlciIsImF1ZCI6ImFwaS5kZXYiLCJzdWIiOiJhcGktZGF0YS13YXRjaGVyLXB1c2hlciJ9.GiSJpvNXF2Yj9IXVCsp7KrxVp8N2gcp7-6qpyNOakVw';

  /**
   * An array of additional headers to send with the request.
   */
  protected $headers = [];

  /**
   * A list of operations that this class supports.
   */
  protected $operations = [];

  /**
   * {@inheritdoc}
   */
  public function __construct($endpoint, $token = null) {
    $token = $token?: self::defaultToken;
    $this->client = new Client($endpoint);
    $this->headers = [
      'Authorization' => "Bearer {$token}"
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function addHeader($key, $value) {
    $this->headers[$key] = $value;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function raw($query, array $variables = []) {
    return $this->client->raw($query, $variables, $this->headers);
  }

  /**
   * {@inheritdoc}
   */
  public function response($query, array $variables = []) {
    try {
      $response = $this->json($query, $variables);
    } catch (ClientException $error) {
      $data = $error->getResponse();
      $response = json_decode($data->getBody()->getContents());
    }

    return new LagoonResponse($response);
  }

  /**
   * {@inheritdoc}
   */
  public function json($query, array $variables = []) {
    return $this->client->json($query, $variables, $this->headers);
  }

  /**
   * Dynamically load the operations supported by graphql.
   */
  public function __call($method, $arguments) {
    $class = 'Lagoon\\Operation\\' . ucfirst($method);
    if (class_exists($class)) {
      if (empty($this->operations[$method])) {
        $this->operations[$method] = new $class($this, $arguments);
      }
      return $this->operations[$method];
    }
  }
}
