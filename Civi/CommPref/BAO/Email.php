<?php

namespace Civi\CommPref\BAO;

class Email {

  /**
   * Function to save the email
   *
   * @param int $contactId
   * @param string $email
   *
   * @return void
   */
  public static function process($contactId, $submittedEmail) {
    // get email information
    $currentEmail = self::getEmail($contactId);

    // if submitted email is same as current email, return
    if ($submittedEmail == $currentEmail) {
      return;
    }

    // get location types
    [$primaryLocationTypeId, $otherLocationTypeId] = self::getLocationTypes();

    // check if email verification is enabled
    $verifyEmail = \Civi::settings()->get('commpref_verify_email');

    // if email verification is enabled, and the new email does not belong to this contact
    // follow verification process:
    // 1. add new email to contact with location type configured in the settings
    // 2. get the configured verification template from settings
    // 3. send the email to the new email
    if ($verifyEmail && !self::checkEmailExist($contactId, $submittedEmail)) {
      // add submitted email to contact with location type as per settings
      $locationTypeId = \Civi::settings()->get('commpref_verify_location_type');
      self::addEmail($contactId, $submittedEmail, $locationTypeId);

      // send verification email
      // $emailTemplate = \Civi::settings()->get('commpref_verify_email_template');

      // TODOS: need to implement email sending
    }
    else {
      // update current email as 'Other' location type
      self::updateEmail($contactId, $currentEmail, $otherLocationTypeId);

      // update the email to primary location type
      self::updateEmail($contactId, $submittedEmail, $primaryLocationTypeId, TRUE);
    }

    return [
      'prev' => $submittedEmail,
      'current' => $currentEmail,
    ];
  }

  /**
   * Function to get email
   *
   * @param int $contactId
   *
   * @return array
   */
  public static function getEmail($contactId) {
    // get primary email
    $emails = \Civi\Api4\Email::get(FALSE)
      ->addSelect('email')
      ->addWhere('contact_id', '=', $contactId)
      ->addWhere('is_primary', '=', TRUE)
      ->execute();

    return $emails[0]['email'];
  }

  /**
   * Function to check for existing email for a contact
   *
   * @param int $contactId
   * @param string $email
   *
   * @return bool
   */
  public static function checkEmailExist($contactId, $email) {
    $emails = \Civi\Api4\Email::get(FALSE)
      ->addSelect('email')
      ->addWhere('contact_id', '=', $contactId)
      ->addWhere('email', '=', $email)
      ->execute();

    return $emails->rowCount ? TRUE : FALSE;
  }

  /**
   * Function to update email
   *
   * @param int $contactId
   * @param string $email
   * @param string $locationTypeId
   * @param bool $isPrimary
   *
   * @return void
   */
  public static function updateEmail($contactId, $email, $locationTypeId, $isPrimary = FALSE) {
    \Civi\Api4\Email::update(FALSE)
      ->addWhere('contact_id', '=', $contactId)
      ->addWhere('email', '=', $email)
      ->setValues(['location_type_id' => $locationTypeId, 'is_primary' => $isPrimary])
      ->execute();
  }

  /**
   * Function to add email
   *
   * @param int $contactId
   * @param string $email
   * @param string $locationTypeId
   *
   * @return void
   */
  public static function addEmail($contactId, $email, $locationTypeId) {
    \Civi\Api4\Email::create(FALSE)
      ->setValues(['contact_id' => $contactId, 'email' => $email, 'location_type_id' => $locationTypeId])
      ->execute();
  }

  /**
   * Function to get location types
   *
   * @return array
   */
  public static function getLocationTypes() {
    // get location types
    $locationTypes = \Civi\Api4\LocationType::get(FALSE)
      ->addSelect('id', 'name', 'is_default')
      ->execute();

    // get location type id
    $primaryLocationTypeId = NULL;
    $otherLocationTypeId = NULL;
    foreach ($locationTypes as $locationType) {
      if ($locationType['is_default']) {
        $primaryLocationTypeId = $locationType['id'];
      }

      if ($locationType['name'] == 'Other') {
        $otherLocationTypeId = $locationType['id'];
      }
    }

    return [$primaryLocationTypeId, $otherLocationTypeId];
  }

}
