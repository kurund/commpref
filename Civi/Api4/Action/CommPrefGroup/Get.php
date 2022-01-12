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
    // get contact id
    $contactId = $this->_itemsToGet('contact_id')[0];

    // get all public groups
    $publicGroups = \Civi\Api4\Group::get(FALSE)
      ->addWhere('visibility', '=', 'Public Pages')
      ->execute();

    // get all contact groups
    $contactGroups = \Civi\Api4\GroupContact::get(FALSE)
      ->addWhere('contact_id', '=', $contactId)
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

      $groups['group_' . $publicGroup['id']] = $contactIsPartOfGroup;
    }

    // get contact user optout value
    $contacts = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('is_opt_out')
      ->addWhere('id', '=', $contactId)
      ->execute();

    $result[] = [
      'contact_id' => $contactId,
      'email_optout' => $contacts[0]['is_opt_out'],
    ] + $groups;

  }

}
