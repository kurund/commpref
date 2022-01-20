<?php

namespace Civi\CommPref\BAO;

class Email {

  /**
   * Function to save the email
   *
   * @param int $contactId
   * @param string $submittedEmail
   * @param boolean $skipEmailVerification
   *
   * @return void
   */
  public static function process($contactId, $submittedEmail, $skipEmailVerification = FALSE) {
    // get email information
    $currentEmail = self::getEmail($contactId);

    // if submitted email is same as current email, return
    if (!empty($currentEmail) && $submittedEmail == $currentEmail) {
      return;
    }

    // get location types
    [$primaryLocationTypeId, $otherLocationTypeId] = self::getLocationTypes();

    // check if email verification is enabled
    $verifyEmail = \Civi::settings()->get('commpref_verify_email');

    // get the verification location type as per settings
    $verifyLocationTypeId = \Civi::settings()->get('commpref_verify_location_type');

    // check if email exists for the contact
    $existingEmailLocationTypeId = self::checkEmailExist($contactId, $submittedEmail);

    if ($verifyEmail && !empty($verifyLocationTypeId) && $verifyLocationTypeId == $existingEmailLocationTypeId) {
      // since email is unverified do nothing
      return;
    }

    if (!$existingEmailLocationTypeId) {
      // if email verification is enabled, and skipEmailVerification is FALSE
      // skipEmailVerification can we set as TRUE using OptIn API
      $isPrimaryLocation = FALSE;
      $underVerification = FALSE;
      if ($verifyEmail && !$skipEmailVerification) {
        $underVerification = TRUE;
        $locationTypeId = $verifyLocationTypeId;
        // TODOS: send verification email
        self::sendVerificationEmail($contactId, $submittedEmail);
      }
      else {
        // set primary location type as location type
        $locationTypeId = $primaryLocationTypeId;
        $isPrimaryLocation = TRUE;

        // check if they have any current email if yes then move it to other location type
        if (!empty($currentEmail)) {
          // update the current email to other location type
          self::updateEmail($contactId, $currentEmail, $otherLocationTypeId);
        }
      }

      self::addEmail($contactId, $submittedEmail, $locationTypeId, $isPrimaryLocation);
    }
    else {
      // update current email as 'Other' location type
      self::updateEmail($contactId, $currentEmail, $otherLocationTypeId);

      // update the email to primary location type
      self::updateEmail($contactId, $submittedEmail, $primaryLocationTypeId, TRUE);
    }

    return [
      'prev' => $currentEmail,
      'new' => (!$underVerification) ? $submittedEmail : $submittedEmail . ' (Needs Verification)',
    ];
  }

  /**
   * Function to send verification email
   *
   * @param int $contactId
   * @param string $email
   *
   * @return void
   */
  public static function sendVerificationEmail($contactId, $email) {
    // get verification template

    // send email

  }

  /**
   * Function to get email
   *
   * @param int $contactId
   *
   * @return email if exists else null
   */
  public static function getEmail($contactId) {
    // get primary email
    $emails = \Civi\Api4\Email::get(FALSE)
      ->addSelect('email')
      ->addWhere('contact_id', '=', $contactId)
      ->addWhere('is_primary', '=', TRUE)
      ->execute();

    return ($emails[0]['email']) ? $emails[0]['email'] : NULL;
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
      ->addSelect('email', 'location_type_id')
      ->addWhere('contact_id', '=', $contactId)
      ->addWhere('email', '=', $email)
      ->execute();

    return $emails->rowCount ? $emails[0]['location_type_id'] : FALSE;
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
   * @param bool $isPrimary
   *
   * @return void
   */
  public static function addEmail($contactId, $email, $locationTypeId, $isPrimary = FALSE) {
    \Civi\Api4\Email::create(FALSE)
      ->setValues([
        'contact_id' => $contactId,
        'email' => $email,
        'location_type_id' => $locationTypeId,
        'is_primary' => $isPrimary,
      ])
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
