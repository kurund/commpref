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

    print_r($submittedGroups);
    //
    // Array ( [email_optout] => [group_2] => [group_3] => [group_4] => 1 )

    // get all public groups

    // get the current groups for this contact

    // updated the groups with approriate status

    // update contact optout field
    \Civi\Api4\Contact::update(FALSE)
      ->addValue('is_opt_out', $submittedGroups['email_optout'])
      ->addWhere('id', '=', $contactId)
      ->execute();

    exit;
    return [
      'prev' => '',
      'current' => '',
    ];
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
    $groups = [];
    foreach ($groups as $publicGroup) {
      $contactIsPartOfGroup = FALSE;
      foreach ($currentGroups as $contactGroup) {
        if ($contactGroup['group_id'] == $publicGroup['id']) {
          $contactIsPartOfGroup = TRUE;
          break;
        }
      }

      $groups['group_' . $publicGroup['id']] = $contactIsPartOfGroup;
    }

    return $groups;

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
