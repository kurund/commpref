<?php

namespace Civi\Api4\Action\CommPrefEmail;

use Civi\Api4\Generic\Result;

/**
 * Get API gets the commuication preferences for a contact.
 *
 * @inheritDoc
 * @package Civi\Api4\Action\CommPrefEmail
 */
class Get extends \Civi\Api4\Generic\BasicGetAction {

  public function _run(Result $result) {
    $result[] = [
      'contact_id' => 202,
      'email' => 'hello@example.org',
    ];
  }

}
