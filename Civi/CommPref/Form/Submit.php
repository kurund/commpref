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
    // comm pref updated
    // privacy policy accepted

  }

}
