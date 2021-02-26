<?php

namespace Lagoon\Query\Users;

use Lagoon\LagoonQueryBase;

/**
 * Fetch all projects from Lagoon.
 */
class FetchAll extends LagoonQueryBase {

  /**
   * {@inheritdoc}
   */
  protected function expectedKeys() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  protected function query() {
    return <<<'QUERY'
query findAll {
   allGroups {
    members {
      user {
        id
        email
      }
    }
  }
}
QUERY;
  }
}
