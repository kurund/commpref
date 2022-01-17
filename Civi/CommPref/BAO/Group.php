<?php

namespace Civi\CommPref\BAO;

class Group {

  /**
   * Function to process groups
   *
   * @param int $contactId
   * @param array $submittedGroups
   *
   * @return void
   */
  public static function process($contactId, $submittedGroups) {
    // submitted Array ( [email_optout] => [group_2] => [group_3] => [group_4] => 1 )
    // get current optout status
    $currentOptout = self::getContactOptOutStatus($contactId);

    // get the current groups for this contact
    $currentGroups = self::getCurrentGroups($contactId);

    // loop through submitted groups
    foreach ($submittedGroups as $field => $value) {
      // check if it's a group field
      if (strpos($field, 'group_') === FALSE) {
        continue;
      }

      // get group id
      $groupId = str_replace('group_', '', $field);

      // check if the group is in the current groups
      $currentGroupInfo = [];
      foreach ($currentGroups as $currentGroup) {
        if ($currentGroup['group_id'] == $groupId) {
          $currentGroupInfo = $currentGroup;
          break;
        }
      }

      // check the sumbitted value
      // if the value is 1, and not part of current groups, add the group
      // if the value is 1, and part of current groups, update group status to 'Added'
      if ($value == 1) {
        // add the group
        if (empty($currentGroupInfo)) {
          self::addGroup($contactId, $groupId);
        }
        // update group status to 'Added'
        elseif ($currentGroupInfo['status'] != 'Added') {
          self::updateGroupStatus($contactId, $groupId, 'Added');
        }
        // else do nothing
      }
      else {
        // if the value is 0, and part of current groups, update group status to 'Removed'
        if (!empty($currentGroupInfo) && $currentGroupInfo['status'] != 'Removed') {
          self::updateGroupStatus($contactId, $groupId, 'Removed');
        }
        // if the value is 0, and not part of current groups, do nothing
      }

    }

    // update contact optout field
    self::updateOptOut($contactId, $submittedGroups['email_optout']);

    return [
      'prev' => ['optout' => $currentOptout, 'groups' => $currentGroups],
      'current' => ['optout' => $submittedGroups['email_optout'], 'groups' => $submittedGroups],
    ];
  }

  /**
   * Function to add contact to group
   *
   * @param int $contactId
   * @param int $groupId
   *
   * @return void
   */
  public static function addGroup($contactId, $groupId) {
    // add contact to group
    \Civi\Api4\GroupContact::create(FALSE)
      ->addValue('group_id', $groupId)
      ->addValue('contact_id', $contactId)
      ->addValue('status:name', 'Added')
      ->execute();
  }

  /**
   * Function to update contact group status
   *
   * @param int $contactId
   * @param int $groupId
   *
   * @return void
   */
  public static function updateGroupStatus($contactId, $groupId, $status = 'Removed') {
    // update group status to removed
    \Civi\Api4\GroupContact::update(FALSE)
      ->addValue('status:name', $status)
      ->addWhere('group_id', '=', $groupId)
      ->addWhere('contact_id', '=', $contactId)
      ->execute();
  }

  /**
   * Function to update optout field
   *
   * @param int $contactId
   * @param int $optout
   *
   * @return void
   */
  public static function updateOptOut($contactId, $optout) {
    \Civi\Api4\Contact::update(FALSE)
      ->addValue('is_opt_out', $optout)
      ->addWhere('id', '=', $contactId)
      ->execute();
  }

  /**
   * Function to get all public groups
   *
   * @return array
   */
  public static function getPublicGroups() {
    $groups = \Civi\Api4\Group::get(FALSE)
      ->addSelect('id', 'title')
      ->addWhere('visibility', '=', 'Public Pages')
      ->addWhere('is_active', '=', 1)
      ->execute();

    return $groups;
  }

  /**
   * Function to get the current groups for this contact
   *
   * @param int $contactId
   *
   * @return array
   */
  public static function getCurrentGroups($contactId) {
    $groups = \Civi\Api4\GroupContact::get(FALSE)
      ->addSelect('group_id', 'status')
      ->addWhere('contact_id', '=', $contactId)
      ->execute();

    return $groups;
  }

  /**
   * Function to build the group data afform rendering
   *
   * @param array $groups
   * @param array $currentGroups
   *
   * @return array
   */
  public static function buildGroupData($groups, $currentGroups) {
    // build groups array based on contact groups
    $buildGroups = [];
    foreach ($groups as $publicGroup) {
      $contactIsPartOfGroup = FALSE;
      foreach ($currentGroups as $contactGroup) {
        if ($contactGroup['group_id'] == $publicGroup['id'] && $contactGroup['status'] == 'Added') {
          $contactIsPartOfGroup = TRUE;
          break;
        }
      }

      $buildGroups['group_' . $publicGroup['id']] = $contactIsPartOfGroup;
    }

    return $buildGroups;

  }

  /**
   * Function to get contact optout status
   *
   * @param int $contactId
   *
   * @return bool
   */
  public static function getContactOptOutStatus($contactId) {
    $contact = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('is_opt_out')
      ->addWhere('id', '=', $contactId)
      ->execute();

    return $contact[0]['is_opt_out'];
  }

}
