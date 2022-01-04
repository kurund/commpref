<?php
namespace Civi\Api4;

use Civi\Api4\Generic\BasicGetFieldsAction;

/**
 * CommPref entity.
 *
 * Provided by the Communication Preference extension.
 *
 * @package Civi\Api4
 */
class CommPref extends Generic\BasicEntity {

  /**
   * CommPref GetGroup.
   *
   * @return \Civi\Api4\Action\CommPref\GetGroup
   *
   * @param bool $checkPermissions
   *
   * @throws \API_Exception
   */
  public static function getGroup($checkPermissions = TRUE): Action\CommPref\GetGroup {
    // return (new \Civi\Api4\Action\CommPref\GetGroup(__CLASS__, __FUNCTION__))
    //   ->setCheckPermissions($checkPermissions);

    $action = new \Civi\Api4\Action\CommPref\GetGroup(static::class, __FUNCTION__);
    return $action->setCheckPermissions($checkPermissions);
  }

  /**
   * Get permissions.
   *
   * It may be that we don't need a permission check on this api at all at there is a check on the entity
   * retrieved.
   *
   * @return array
   */
  public static function permissions():array {
    return ['getGroup' => 'access CiviCRM'];
  }

  /**
   * @return \Civi\Api4\Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function(BasicGetFieldsAction $self) {
      return [
        [
          'name' => 'contact_id',
          'data_type' => 'Integer',
          'title' => 'Contact ID',
          'required' => TRUE,
          'fk_entity' => 'Contact',
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }

}
