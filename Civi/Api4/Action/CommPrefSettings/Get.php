<?php

namespace Civi\Api4\Action\CommPrefSettings;

use Civi\Api4\Generic\Result;

/**
 * Get API gets the commuication preference settings
 *
 * @inheritDoc
 * @package Civi\Api4\Action\CommPrefSettings
 */
class Get extends \Civi\Api4\Generic\BasicGetAction {

  public function _run(Result $result) {
    // return comm pref settings
    $result[] = [
      'commpref_verify_email' => \Civi::settings()->get('commpref_verify_email'),
      'commpref_verify_location_type' => \Civi::settings()->get('commpref_verify_location_type'),
      'commpref_custom_unsubscribe' => \Civi::settings()->get('commpref_custom_unsubscribe'),
    ];
  }

}
