<?php

namespace Civi\CommPref\BAO;

class Activity {

  /**
   * Function to record activity
   *
   * @param array $params
   *
   * @return void
   */
  public static function record($params) {
    $commonActivityParams = [
      'source_contact_id' => $params['contact_id'],
      'target_contact_id' => $params['contact_id'],
      'status_id:name' => 'Completed',
      'activity_date_time' => date('YmdHis'),
    ];

    if (!empty($params['source'])) {
      $commonActivityParams['subject'] = $params['source'];
    }

    \Civi\Api4\Activity::create()
      ->setValues($commonActivityParams + $params)
      ->execute();
  }

}
