<?php
// Adds activity type for CommPref

return [
  [
    'name' => 'UpdateCommunicationPreferences',
    'entity' => 'OptionValue',
    'params' => [
      'option_group_id' => 'activity_type',
      'name' => 'update_communication_preferences',
      'label' => 'Update communication preferences',
    ],
  ],
  [
    'name' => 'AcceptPrivacyPolicy',
    'entity' => 'OptionValue',
    'params' => [
      'option_group_id' => 'activity_type',
      'name' => 'accept_privacy_policy',
      'label' => 'Accept privacy policy',
    ],
  ],
  [
    'name' => 'Unverified',
    'entity' => 'LocationType',
    'params' => [
      'name' => 'Unverified',
      'display_name' => 'Unverified',
      'is_reserved' => 1,
      'is_active' => 1,
      'description' => 'Unverified location',
      'vcard_name' => 'Unverified',
    ],
  ],
  [
    'name' => 'msg_tpl_workflow_commpref',
    'entity' => 'OptionGroup',
    'params' => [
      'name' => 'msg_tpl_workflow_commpref',
      'title' => 'Message Template Workflow for Communication Preferences',
      'description' => 'Message Template Workflow for Communication Preferences',
    ],
  ],
  [
    'name' => 'CommPrefEmailVerification',
    'entity' => 'OptionValue',
    'params' => [
      'option_group_id' => 'msg_tpl_workflow_commpref',
      'name' => 'commpref_email_verification',
      'label' => 'Email verification template',
    ],
  ],
  [
    'name' => 'EmailVerification',
    'entity' => 'MessageTemplate',
    'params' => [
      'msg_title' => 'Email Verification',
      'msg_subject' => 'Email Verification',
      'msg_text' => file_get_contents(dirname(__FILE__) . '/workflow_templates/email_verification_text.tpl'),
      'msg_html' => file_get_contents(dirname(__FILE__) . '/workflow_templates/email_verification_html.tpl'),
      'workflow_name' => 'commpref_email_verification',
    ],
  ],
];
