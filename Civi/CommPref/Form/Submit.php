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

    // record activity
    $commonActivityParams = [
      'source_contact_id' => $contactId,
      'target_contact_id' => $contactId,
      'status_id' => 'Completed',
      'activity_date_time' => date('YmdHis'),
    ];

    // comm pref updated
    $commPrefActivityParams = [
      'subject' => 'Communication Preference Updated',
      'activity_type_id' => 'update_communication_preferences',
    ];

    \Civi\CommPref\BAO\Activity::record($commonActivityParams + $commPrefActivityParams);

    // privacy policy accepted
    $privacyActivityParams = [
      'subject' => 'Communication Preference Updated',
      'activity_type_id' => 'accept_privacy_policy',
    ];

    \Civi\CommPref\BAO\Activity::record($commonActivityParams + $privacyActivityParams);

  }

}
