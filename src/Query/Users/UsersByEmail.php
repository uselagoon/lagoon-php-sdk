<?php

namespace Lagoon\Query\Users;

use Lagoon\LagoonQueryBase;

/**
 * Update a project using the grpahql api.
 */
class UsersByEmail extends LagoonQueryBase {

  /**
   * {@inheritdoc}
   */
  protected function expectedKeys() {
    return ['email'];
  }

  /**
   * {@inheritdoc}
   */
  protected function query() {
    return <<<QUERY
query FindByEmail(\$email: String!) {
  userByEmail(email: \$email) {
    %s
  }
}
QUERY;
  }
}
