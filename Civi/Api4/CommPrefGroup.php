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
      $fields = [
        [
          'name' => 'contact_id',
          'data_type' => 'Integer',
          'title' => 'Contact ID',
          'required' => TRUE,
          'fk_entity' => 'Contact',
        ],
        [
          'name' => 'email_optout',
          'data_type' => 'Boolean',
          'title' => 'Opt out of email',
          "input_type" => "CheckBox",
          "label" => "I don't want to receive emails from Population Matters.",
        ],
      ];

      // loop through pubic group and build the checkboxes
      // get all public groups
      $publicGroups = \Civi\Api4\Group::get(FALSE)
        ->addWhere('visibility', '=', 'Public Pages')
        ->execute();

      foreach ($publicGroups as $group) {
        $fields[] = [
          'name' => "group_" . $group['id'],
          'data_type' => 'Boolean',
          'title' => $group['title'],
          'required' => FALSE,
          'label' => ($group['frontend_description']) ? $group['frontend_description'] : $group['title'],
        ];
      }

      return $fields;
    }))->setCheckPermissions($checkPermissions);
  }

}
