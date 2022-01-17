<?php
namespace Civi\Api4;

/**
 * CommPrefOptIn entity.
 *
 * Provided by the Communication Preference extension.
 *
 * @package Civi\Api4
 */
class CommPrefOptIn extends Generic\AbstractEntity {

  /**
   * @param bool $checkPermissions
   * @return Action\CommPrefOptIn\Create
   */
  public static function create($checkPermissions = TRUE) {
    return (new Action\CommPrefOptIn\Create(__CLASS__, __FUNCTION__))
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
          'name' => 'groups',
          'data_type' => 'String',
          "description" => "Comma separated list of group id's",
          'title' => 'Group IDs',
          'label' => 'Group IDs',
          'required' => TRUE,
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
