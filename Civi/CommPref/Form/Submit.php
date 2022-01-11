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

    // process emails
    \Civi\CommPref\BAO\Email::process($contactId, $submittedValues[0]['joins']['CommPrefEmail']);

    // process phone
    \Civi\CommPref\BAO\Phone::process($contactId, $submittedValues[0]['joins']['CommPrefPhone']);

  }

}
