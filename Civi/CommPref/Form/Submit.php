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
    $commPrefActivityDetails = self::formatActivityDetails([
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
   * Function to format activity details for comm pref
   *
   * @param array $params
   *
   * @return string
   */
  public static function formatActivityDetails($params) {
    $details = '';
    foreach ($params as $key => $value) {
      if (empty($value)) {
        continue;
      }

      switch ($key) {
        case 'groups':

          if ($value['prev']['optout'] != $value['new']['optout']) {
            $details .= '<br />' . ($value['new']['optout'] ? 'Contact has opted out' : 'Contact has opted in');
          }
          break;

        case 'email':
          $details .= '<br />' . 'New Email: ' . $value['new'];
          if (!empty($value['prev'])) {
            $details .= '<br />' . 'Previous Email: ' . $value['prev'];
          }
          break;

        case 'phone':
          if ($value['prev'][0] != $value['new'][0]) {
            $details .= '<br />' . 'New Mobile Number: ' . $value['new'][0];
            if (!empty($value['prev'][0])) {
              $details .= '<br />' . 'Previous Mobile Number: ' . $value['prev'][0];
            }
          }
          if ($value['prev'][1] != $value['new'][1]) {
            $details .= '<br />' . 'New Landline Number: ' . $value['new'][1];
            if (!empty($value['prev'][1])) {
              $details .= '<br />' . 'Previous Landline Number: ' . $value['prev'][1];
            }
          }
          break;
      }
    }

    return $details;
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
