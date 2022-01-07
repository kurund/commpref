<?php
namespace Civi\Api4;

/**
 * CommPref entity.
 *
 * Provided by the Communication Preference extension.
 *
 * @package Civi\Api4
 */
class CommPrefGroup extends Generic\AbstractEntity {

  /**
   * @param bool $checkPermissions
   * @return Action\CommPrefGroup\Get
   */
  public static function get($checkPermissions = TRUE) {
    return (new Action\CommPrefGroup\Get(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @return \Civi\Api4\Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      // TODOS: need to loop through group and build the checkboxes

      return [
        [
          'name' => 'contact_id',
          'data_type' => 'Integer',
          'title' => 'Contact ID',
          'required' => TRUE,
          'fk_entity' => 'Contact',
        ],
        [
          'name' => 'groups',
          'data_type' => 'Array',
          'title' => 'Groups',
          'description' => 'Array of group IDs',
        ],
        [
          'name' => 'email_optout',
          'data_type' => 'Boolean',
          'title' => 'Opt out of email',
          "input_type" => "Checkbox",
          "label" => "I don't want to receive emails from Population Matters.",
        ],

      ];
    }))->setCheckPermissions($checkPermissions);
  }

}
