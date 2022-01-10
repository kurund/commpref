<?php

namespace Civi\Api4\Action\CommPrefPhone;

use Civi\Api4\Generic\Result;

/**
 * Get API gets the communication preferences for a contact.
 *
 * @inheritDoc
 * @package Civi\Api4\Action\CommPrefPhone
 */
class Get extends \Civi\Api4\Generic\BasicGetAction {

  public function _run(Result $result) {
    // get contact id
    $contactId = $this->_itemsToGet('contact_id')[0];

    // get phone information
    [$mobileNumber, $landlineNumber] = \Civi\CommPref\BAO\Phone::getPhone($contactId);

    $result[] = [
      'contact_id' => $contactId,
      'phone_mobile' => $mobileNumber,
      'phone_landline' => $landlineNumber,
    ];
  }

}
