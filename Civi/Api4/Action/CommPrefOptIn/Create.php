<?php

namespace Civi\Api4\Action\CommPrefOptIn;

/**
 * Update API commuication preference opt in action
 *
 * @inheritDoc
 * @package Civi\Api4\Action\CommPrefOptIn
 */
class Create extends \Civi\Api4\Generic\BasicCreateAction {

  protected function writeRecord($item) {
    // check if email is passed andf process email update
    $emailDetails = [];
    if (!empty($item['email'])) {
      $emailDetails = \Civi\CommPref\BAO\Email::process($item['contact_id'], $item['email']);
    }

    // process groups and update opt out
    // format group params
    // expected group params: Array ( [email_optout] => [group_2] => [group_3] => [group_4] => 1 )
    $groupParams = self::formatGroupParams($item['groups']);

    // enable email opt in
    $groupParams += ['email_opt_out' => 0];

    $groupDetails = \Civi\CommPref\BAO\Group::process($item['contact_id'], $groupParams);

    return $item;

  }

  /**
   * Format group string to array
   *
   * @param string $groups
   *
   * @return array
   */
  public static function formatGroupParams($groups) {
    $groupParams = [];
    $submittedGroups = explode(',', $groups);
    foreach ($submittedGroups as $groupId) {
      if (empty($groupId) || !is_numeric($groupId)) {
        continue;
      }
      $groupParams['group_' . $groupId] = TRUE;
    }
    return $groupParams;
  }

}
