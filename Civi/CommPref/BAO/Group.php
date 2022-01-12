<?php

namespace Civi\CommPref\BAO;

class Group {

  /**
   * Function to process groups
   *
   * @param int $contactId
   * @param array $params
   *
   * @return void
   */
  public static function process($contactId, $params) {
    // submitted values
    $submittedGroups = $params[0];

    return [
      'prev' => '',
      'current' => '',
    ];
  }

}
