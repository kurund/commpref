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
    // get primary email
    $emails = \Civi\Api4\Email::get(FALSE)
      ->addSelect('email')
      ->addWhere('contact_id', '=', $this->_itemsToGet('contact_id')[0])
      ->addWhere('is_primary', '=', TRUE)
      ->execute();

    $result[] = [
      'contact_id' => $this->_itemsToGet('contact_id')[0],
      'email' => $emails[0]['email'],
    ];
  }

}
