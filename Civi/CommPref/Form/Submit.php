<?php

namespace Civi\CommPref\Form;

class Submit {

  public function process($event) {

    $submittedValues = $event->getRecords();

    // hack to check if commpref form is submitted
    if (empty($submittedValues[0]['joins']['CommPrefGroup'])) {
      return;
    }

    // process groups

    // process emails

    // process phone
    \Civi\CommPref\BAO\Phone::processPhone($submittedValues[0]['fields']['id'], $submittedValues[0]['joins']['CommPrefPhone']);

  }

}
