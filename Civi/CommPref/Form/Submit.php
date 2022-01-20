<?php

namespace Civi\CommPref\Form;

class Submit {

  public function process($event) {

    $submittedValues = $event->getRecords();

    // hack to check if commpref form is submitted
    if (empty($submittedValues[0]['joins']['CommPrefGroup'])) {
      return;
    }

    // get contact id
    $contactId = $submittedValues[0]['fields']['id'];

    // process groups
    $groupData = \Civi\CommPref\BAO\Group::process($contactId, $submittedValues[0]['joins']['CommPrefGroup'][0]);

    // process emails
    $emailData = \Civi\CommPref\BAO\Email::process(
      $contactId, $submittedValues[0]['joins']['CommPrefEmail'][0]['email']);

    // process phone
    $phoneData = \Civi\CommPref\BAO\Phone::process($contactId, $submittedValues[0]['joins']['CommPrefPhone']);

    // record activities
    // 1. comm pref updated
    $commPrefActivityDetails = \Civi\CommPref\BAO\Activity::formatActivityDetails([
      'groups' => $groupData,
      'email' => $emailData,
      'phone' => $phoneData,
    ]);

    $commPrefActivityParams = [
      'activity_type_id:name' => 'update_communication_preferences',
      'details' => $commPrefActivityDetails,
    ];

    self::recordActivity($contactId, $commPrefActivityParams);

    // 2. privacy policy accepted
    $privacyActivityParams = [
      'activity_type_id:name' => 'accept_privacy_policy',
    ];

    self::recordActivity($contactId, $privacyActivityParams);
  }

  /**
   * Function to record activity
   *
   * @param int $contactId
   * @param array $params
   *
   * @return void
   */
  public static function recordActivity($contactId, $params) {
    $commonActivityParams = [
      'subject' => 'Communication Preference Updated',
      'contact_id' => $contactId,
    ];

    \Civi\CommPref\BAO\Activity::record($commonActivityParams + $params);
  }

}
