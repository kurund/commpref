<?php

namespace Civi\Api4\Action\CommPrefPhone;

use Civi\Api4\Generic\Result;

/**
 * Get API gets the commuication preferences for a contact.
 *
 * @inheritDoc
 * @package Civi\Api4\Action\CommPrefPhone
 */
class Get extends \Civi\Api4\Generic\BasicGetAction {

  public function _run(Result $result) {
    $result[] = [
      'contact_id' => 202,
      'phone_mobile' => '12222222',
      'phone_landline' => '21212121',
    ];
  }

}
