<?php

namespace Civi\CommPref\Form;

class Settings {

  public function process($event) {

    $submittedValues = $event->getRecords();

    // hack to check if commpref form is submitted
    if (empty($submittedValues[0]['joins']['CommPrefSettings'])) {
      return;
    }

    // update settings
    \Civi::settings()->set('commpref_verify_email', $submittedValues[0]['joins']['CommPrefSettings'][0]['commpref_verify_email']);
    \Civi::settings()->set('commpref_verify_location_type', $submittedValues[0]['joins']['CommPrefSettings'][0]['commpref_verify_location_type']);
    \Civi::settings()->set('commpref_custom_unsubscribe', $submittedValues[0]['joins']['CommPrefSettings'][0]['commpref_custom_unsubscribe']);
  }

}
