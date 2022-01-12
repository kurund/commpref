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
    $publicGroups = \Civi\CommPref\BAO\Group::getPublicGroups();

    // get all contact groups
    $contactGroups = \Civi\CommPref\BAO\Group::getCurrentGroups($contactId);

    // build data for rendering
    $groups = \Civi\CommPref\BAO\Group::buildGroupData($publicGroups, $contactGroups);

    // get contact optout value
    $contactOptout = \Civi\CommPref\BAO\Group::getContactOptOutStatus($contactId);

    $result[] = [
      'contact_id' => $contactId,
      'email_optout' => $contactOptout,
    ] + $groups;

  }

}
