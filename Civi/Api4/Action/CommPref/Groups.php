<?php

namespace Civi\Api4\Action\CommPref;

use Civi\Api4\Generic\Result;

/**
 * Groups API gets the
 *
 * @see \Civi\Api4\Generic\AbstractAction
 *
 * @package Civi\Api4\Action\CommPref
 */
class Groups extends \Civi\Api4\Generic\AbstractAction {

  /**
   * Contact id
   * @var int
   *
   * We can make a parameter required with this annotation:
   * @required
   */
  protected $contact_id;

  /**
   * Every action must define a _run function to perform the work and place results in the Result object.
   *
   * When using the set of Basic actions, they define _run for you and you just need to provide a getter/setter function.
   *
   * @param \Civi\Api4\Generic\Result $result
   */
  public function _run(Result $result) {
    // get all public groups
    $publicGroups = \Civi\Api4\Group::get(FALSE)
      ->addWhere('visibility', '=', 'Public Pages')
      ->execute();

    // get all contact groups
    $contactGroups = \Civi\Api4\GroupContact::get()
      ->addWhere('contact_id', '=', $this->contact_id)
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

    $result[] = $groups;
  }

  /**
   * Declare ad-hoc field list for this action.
   *
   * Some actions return entirely different data to the entity's "regular" fields.
   *
   * This is a convenient alternative to adding special logic to our GetFields function to handle this action.
   *
   * @return array
   */
  public static function fields() {
    return [
      ['name' => 'contact_id', 'data_type' => 'Integer', 'title' => 'Contact ID', 'fk_entity' => 'Contact'],
    ];
  }

}
