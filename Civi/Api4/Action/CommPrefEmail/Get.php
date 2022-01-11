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
    // get contact id
    $contactId = $this->_itemsToGet('contact_id')[0];

    // get primary email and return
    $result[] = [
      'contact_id' => $contactId,
      'email' => \Civi\CommPref\BAO\Email::getEmail($contactId),
    ];
  }

}
