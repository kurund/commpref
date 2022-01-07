<?php

namespace Civi\Api4\Action\CommPrefGroup;

use Civi\Api4\Generic\Result;

/**
 * Get API gets the commuication preferences for a contact.
 *
 * @inheritDoc
 * @package Civi\Api4\Action\CommPrefGroup
 */
class Get extends \Civi\Api4\Generic\BasicGetAction {

  public function _run(Result $result) {
    // get all public groups
    $publicGroups = \Civi\Api4\Group::get(FALSE)
      ->addWhere('visibility', '=', 'Public Pages')
      ->execute();

    // get all contact groups
    $contactGroups = \Civi\Api4\GroupContact::get(FALSE)
      ->addWhere('contact_id', '=', $this->_itemsToGet('contact_id')[0])
      ->execute();

    // build groups array based on contact groups
    $groups = [];
    foreach ($publicGroups as $publicGroup) {
      $contactIsPartOfGroup = FALSE;
      foreach ($contactGroups as $contactGroup) {
        if ($contactGroup['group_id'] == $publicGroup['id']) {
          $contactIsPartOfGroup = TRUE;
          break;
        }
      }
      $groups[$publicGroup['id']] = [
        'joined' => $contactIsPartOfGroup,
        'title' => $publicGroup['title'],
        'key' => 'group_' . $publicGroup['id'],
      ];
    }

    $result[] = [
      'contact_id' => 202,
      'groups' => $groups,
      'email_optout' => FALSE,
    ];
  }

}
