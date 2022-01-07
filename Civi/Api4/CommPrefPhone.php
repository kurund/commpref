<?php
namespace Civi\Api4;

/**
 * CommPrefPhone entity.
 *
 * Provided by the Communication Preference extension.
 *
 * @package Civi\Api4
 */
class CommPrefPhone extends Generic\AbstractEntity {

  /**
   * @param bool $checkPermissions
   * @return Action\CommPrefPhone\Get
   */
  public static function get($checkPermissions = TRUE) {
    return (new Action\CommPrefPhone\Get(__CLASS__, __FUNCTION__))
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
          'name' => 'phone_mobile',
          'data_type' => 'String',
          'title' => 'Mobile Number',
          "input_type" => "Text",
          "input_attrs" => [
            "maxlength" => 254,
          ],
          "label" => "Mobile Number",
        ],
        [
          'name' => 'phone_landline',
          'data_type' => 'String',
          'title' => 'Landline Number',
          "input_type" => "Text",
          "input_attrs" => [
            "maxlength" => 254,
          ],
          "label" => "Landline Number",
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }

}
