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
  public static function add($params) {
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

  /**
   * Function to record activities
   *
   * @param int $contactId
   * @param array $groupData
   * @param array $emailData
   * @param array $phoneData
   *
   * @return void
   */
  public static function record($contactId, $groupData, $emailData = [], $phoneData = []) {
    // 1. comm pref updated
    $commPrefActivityDetails = self::formatActivityDetails([
      'groups' => $groupData,
      'email' => $emailData,
      'phone' => $phoneData,
    ]);

    $commPrefActivityParams = [
      'activity_type_id:name' => 'update_communication_preferences',
      'details' => $commPrefActivityDetails,
      'contact_id' => $contactId,
    ];

    self::add($commPrefActivityParams);

    // 2. privacy policy accepted
    $privacyActivityParams = [
      'activity_type_id:name' => 'accept_privacy_policy',
      'contact_id' => $contactId,
    ];

    self::add($privacyActivityParams);
  }

  /**
   * Function to format activity details for comm pref
   *
   * @param array $params
   *
   * @return string
   */
  public static function formatActivityDetails($params) {
    $details = '';
    foreach ($params as $key => $value) {
      if (empty($value)) {
        continue;
      }

      switch ($key) {
        case 'groups':
          if ($value['prev']['optout'] != $value['new']['optout']) {
            $details .= '<br />' . ($value['new']['optout'] ? 'Contact has opted out' : 'Contact has opted in');
          }

          if (!empty($value['new']['groups'])) {
            // get all public groups
            $publicGroups = \Civi\CommPref\BAO\Group::getPublicGroups();
            $flatternGroupList = [];
            foreach ($publicGroups as $group) {
              $flatternGroupList[$group['id']] = $group['title'];
            }

            foreach ($value['new']['groups'] as $groupId => $status) {
              if ($status['new'] == 'Added') {
                $details .= '<br />' . $flatternGroupList[$groupId] . ' was added.';

                if (!empty($status['prev'])) {
                  $details .= ' (Prev: ' . $status['prev'] . ')';
                }
              }
              elseif ($status['new'] == 'Removed') {
                $details .= '<br />' . $flatternGroupList[$groupId] . ' was removed.';
              }
            }
          }

          break;

        case 'email':
          $details .= '<br />' . 'New Email: ' . $value['new'];
          if (!empty($value['prev'])) {
            $details .= '<br />' . 'Previous Email: ' . $value['prev'];
          }
          break;

        case 'phone':
          if ($value['prev'][0] != $value['new'][0]) {
            $details .= '<br />' . 'New Mobile Number: ' . $value['new'][0];
            if (!empty($value['prev'][0])) {
              $details .= '<br />' . 'Previous Mobile Number: ' . $value['prev'][0];
            }
          }
          if ($value['prev'][1] != $value['new'][1]) {
            $details .= '<br />' . 'New Landline Number: ' . $value['new'][1];
            if (!empty($value['prev'][1])) {
              $details .= '<br />' . 'Previous Landline Number: ' . $value['prev'][1];
            }
          }
          break;
      }
    }

    return $details;
  }

}
