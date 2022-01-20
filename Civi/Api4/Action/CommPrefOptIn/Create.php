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
    $emailData = [];
    if (!empty($item['email'])) {
      $skipEmailVerification = FALSE;
      if (!empty($item['skip_email_verification'])) {
        $skipEmailVerification = TRUE;
      }
      $emailData = \Civi\CommPref\BAO\Email::process($item['contact_id'], $item['email'], $skipEmailVerification);
    }

    // process groups and update opt out
    // format group params
    // expected group params: Array ( [email_optout] => [group_2] => [group_3] => [group_4] => 1 )
    $groupParams = self::formatGroupParams($item['groups']);

    // enable email opt in
    $groupParams += ['email_opt_out' => 0];

    $groupData = \Civi\CommPref\BAO\Group::process($item['contact_id'], $groupParams);

    // record activity
    self::recordActivity($item, $groupData, $emailData);

    return $item;

  }

  /**
   * Function to record activity
   *
   * @param array $params
   * @param array $groupData
   * @param array $emailData
   *
   * @return void
   */
  public static function recordActivity($params, $groupData, $emailData) {
    // 1. comm pref updated
    $commPrefActivityDetails = \Civi\CommPref\BAO\Activity::formatActivityDetails([
      'groups' => $groupData,
      'email' => $emailData,
    ]);

    $commPrefActivityParams = [
      'activity_type_id:name' => 'update_communication_preferences',
      'details' => $commPrefActivityDetails,
    ];

    \Civi\CommPref\BAO\Activity::record($commPrefActivityParams + $params);

    // 2. privacy policy accepted
    $privacyActivityParams = [
      'activity_type_id:name' => 'accept_privacy_policy',
    ];

    \Civi\CommPref\BAO\Activity::record($privacyActivityParams + $params);
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
