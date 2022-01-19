<?php

namespace Civi\CommPref\BAO;

class Phone {

  /**
   * Function to save the phone number
   *
   * @param int $contactId
   * @param array $params
   *
   * @return void
   */
  public static function process($contactId, $params) {
    // submitted values
    $phoneMobile = $params[0]['phone_mobile'];
    $phoneLandline = $params[0]['phone_landline'];

    // get phone information
    [$mobileNumber, $landlineNumber] = self::getPhone($contactId);

    // return early if phones are same
    if ($phoneMobile == $mobileNumber && $phoneLandline == $landlineNumber) {
      return;
    }

    // update or delete phone numbers
    // mobile
    if (empty($phoneMobile) && !empty($mobileNumber)) {
      self::deletePhone($contactId, 'Mobile');
    }
    elseif (!empty($phoneMobile) && empty($mobileNumber)) {
      self::addPhone($contactId, $phoneMobile, 'Mobile');
    }
    elseif ($phoneMobile != $mobileNumber) {
      self::updatePhone($contactId, $phoneMobile, 'Mobile');
    }

    // landline
    if (empty($phoneLandline) && !empty($landlineNumber)) {
      self::deletePhone($contactId, 'Phone');
    }
    elseif (!empty($phoneLandline) && empty($landlineNumber)) {
      self::addPhone($contactId, $phoneLandline, 'Phone');
    }
    elseif ($phoneLandline != $landlineNumber) {
      self::updatePhone($contactId, $phoneLandline, 'Phone');
    }

    return [
      'prev' => [$mobileNumber, $landlineNumber],
      'new' => [$phoneMobile, $phoneLandline],
    ];
  }

  /**
   * Function to add phone number
   *
   * @param int $contactId
   * @param string $phoneNumber
   * @param string $phoneType
   *
   * @return void
   */
  public static function addPhone($contactId, $phoneNumber, $phoneType) {
    \Civi\Api4\Phone::create(FALSE)
      ->addValue('contact_id', $contactId)
      ->addValue('phone', $phoneNumber)
      ->addValue('phone_type_id:name', $phoneType)
      ->execute();
  }

  /**
   * Function to get phone numbers
   *
   * @param int $contactId
   *
   * @return array
   */
  public static function getPhone($contactId) {
    $phones = \Civi\Api4\Phone::get(FALSE)
      ->addSelect('phone', 'phone_type_id:name')
      ->addWhere('contact_id', '=', $contactId)
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

    return [$mobileNumber, $landlineNumber];
  }

  /**
   * Function to update the phone number
   *
   * @param int $contactId
   * @param string $phone
   * @param string $phoneType
   */
  public static function updatePhone($contactId, $phone, $phoneType) {
    \Civi\Api4\Phone::update(FALSE)
      ->addWhere('contact_id', '=', $contactId)
      ->addWhere('phone_type_id:name', '=', $phoneType)
      ->setValues(['phone' => $phone])
      ->execute();
  }

  /**
   * Function to delete the phone number
   *
   * @param int $contactId
   * @param string $phoneType
   */
  public static function deletePhone($contactId, $phoneType) {
    \Civi\Api4\Phone::delete(FALSE)
      ->addWhere('contact_id', '=', $contactId)
      ->addWhere('phone_type_id:name', '=', $phoneType)
      ->execute();
  }

}
