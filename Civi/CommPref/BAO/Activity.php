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
    \Civi\Api4\Activity::create()
      ->setValues($params)
      ->execute();
  }

}
