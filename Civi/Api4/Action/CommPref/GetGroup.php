<?php
namespace Civi\Api4\Action\CommPref;

use Civi\Api4\Generic\BasicGetAction;
use Civi\Api4\Generic\Result;

/**
 *  Class GetGroup.
 *
 * Provided by the CommPref extension.
 *
 * @package Civi\Api4
 */
class GetGroup extends BasicGetAction {

  /**
   * @inheritDoc
   *
   * @param \Civi\Api4\Generic\Result $result
   *
   * @throws \API_Exception
   */
  public function _run(Result $result) {

    // get all public groups
    $publicGroups = \Civi\Api4\Group::get(FALSE)
      ->addWhere('visibility', '=', 'Public Pages')
      ->execute();

    // get all contact groups
    $contactGroups = \Civi\Api4\GroupContact::get()
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
      $groups[$publicGroup['id']] = $contactIsPartOfGroup;
    }

    $result->exchangeArray($groups);
  }

}
