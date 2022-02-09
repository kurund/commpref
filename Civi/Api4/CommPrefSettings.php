<?php
namespace Civi\Api4;

/**
 * CommPrefSettings entity.
 *
 * Provided by the Communication Preference extension.
 *
 * @package Civi\Api4
 */
class CommPrefSettings extends Generic\AbstractEntity {

  /**
   * @param bool $checkPermissions
   * @return Action\CommPrefSettings\Get
   */
  public static function get($checkPermissions = TRUE) {
    return (new Action\CommPrefSettings\Get(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @return \Civi\Api4\Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      return [
        [
          'name' => 'commpref_verify_email',
          'data_type' => 'Boolean',
          'title' => 'Verify Email',
          "input_type" => "CheckBox",
          "label" => "Enable email verification",
        ],
        [
          'name' => 'commpref_verify_location_type',
          'data_type' => 'Integer',
          'title' => 'Verify Location Type',
          "input_type" => "Select",
          "label" => "Location type for unverified email",
          "options" => \CRM_Core_PseudoConstant::get('CRM_Core_DAO_Address', 'location_type_id'),
        ],
        [
          'name' => 'commpref_custom_unsubscribe',
          'data_type' => 'String',
          'title' => 'Custom unsubscribe path',
          "input_type" => "Text",
          "label" => "Custom unsubscribe path",
          'description' => 'Redirect unsubscribe requests to another path',
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }

}
