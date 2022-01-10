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
    // get phone information
    $phones = \Civi\Api4\Phone::get(FALSE)
      ->addSelect('phone', 'phone_type_id:name')
      ->addWhere('contact_id', '=', $this->_itemsToGet('contact_id')[0])
      ->addWhere('phone_type_id', 'IN', [1, 2])
      ->execute();

    // get phone numbers
    $mobileNumber = '';
    $landlineNumber = '';
    foreach ($phones as $phone) {
      if ($phone['phone_type_id:name'] == 'Mobile') {
        $mobileNumber = $phone['phone'];
      }
      elseif ($phone['phone_type_id:name'] == 'Phone') {
        $landlineNumber = $phone['phone'];
      }
    }

    $result[] = [
      'contact_id' => 202,
      'phone_mobile' => $mobileNumber,
      'phone_landline' => $landlineNumber,
    ];
  }

}
