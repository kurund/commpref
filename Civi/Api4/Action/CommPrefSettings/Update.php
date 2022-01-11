<?php

namespace Civi\Api4\Action\CommPrefSettings;

/**
 * Update API for commuication preference settings
 *
 * @inheritDoc
 * @package Civi\Api4\Action\CommPrefSettings
 */
class Update extends \Civi\Api4\Generic\BasicUpdateAction {

  protected function writeRecord($values) {
    // update comm pref settings
    \Civi::settings()->set('commpref_verify_email', $values['commpref_verify_email']);
    \Civi::settings()->set('commpref_verify_location_type', $values['commpref_verify_location_type']);

    return $values;
  }

}
