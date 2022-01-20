<?php

require_once 'commpref.civix.php';
// phpcs:disable
use CRM_Commpref_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function commpref_civicrm_config(&$config) {
  _commpref_civix_civicrm_config($config);

  // add listener to commumication preference form submit event
  if (isset(Civi::$statics[__FUNCTION__])) {
    return;
  }

  Civi::$statics[__FUNCTION__] = 1;

  Civi::dispatcher()->addListener('civi.afform.submit', ['\Civi\CommPref\Form\Settings', 'process'], 100);
  Civi::dispatcher()->addListener('civi.afform.submit', ['\Civi\CommPref\Form\Submit', 'process'], 101);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function commpref_civicrm_xmlMenu(&$files) {
  _commpref_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function commpref_civicrm_install() {
  _commpref_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function commpref_civicrm_postInstall() {
  _commpref_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function commpref_civicrm_uninstall() {
  _commpref_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function commpref_civicrm_enable() {
  _commpref_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function commpref_civicrm_disable() {
  _commpref_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function commpref_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _commpref_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function commpref_civicrm_managed(&$entities) {
  _commpref_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function commpref_civicrm_caseTypes(&$caseTypes) {
  _commpref_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function commpref_civicrm_angularModules(&$angularModules) {
  _commpref_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function commpref_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _commpref_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function commpref_civicrm_entityTypes(&$entityTypes) {
  _commpref_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function commpref_civicrm_themes(&$themes) {
  _commpref_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function commpref_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function commpref_civicrm_navigationMenu(&$menu) {
  _commpref_civix_insert_navigation_menu($menu, 'Administer/System Settings', [
    'label' => E::ts('Communication Preference Settings'),
    'name' => 'comm-pref-settings',
    'url' => CRM_Utils_System::url('civicrm/communication-preference-settings', NULL, TRUE),
    'permission' => 'access CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ]);
  _commpref_civix_navigationMenu($menu);
}

/**
 * Implement hook_civicrm_tokens
 *
 * Let's add the comm pref tokens to the token list
 *
 * @param $tokens list of tokens
 *
 * @return void
 */
function commpref_civicrm_tokens(&$tokens) {
  $tokens['commpref'] = [
    'commpref.email_verification_url' => 'Email verification URL',
  ];
}
