<?php
namespace Civi\Api4;

/**
 * CommPrefEmail entity.
 *
 * Provided by the Communication Preference extension.
 *
 * @package Civi\Api4
 */
class CommPrefEmail extends Generic\AbstractEntity {

  /**
   * @param bool $checkPermissions
   * @return Action\CommPrefEmail\Get
   */
  public static function get($checkPermissions = TRUE) {
    return (new Action\CommPrefEmail\Get(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @return \Civi\Api4\Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      return [
        [
          'name' => 'contact_id',
          'data_type' => 'Integer',
          'title' => 'Contact ID',
          'required' => TRUE,
          'fk_entity' => 'Contact',
        ],
        [
          'name' => 'email',
          'data_type' => 'String',
          'title' => 'Email',
          "input_type" => "Text",
          "input_attrs" => [
            "maxlength" => 254,
          ],
          "label" => "Email address",
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }

}
