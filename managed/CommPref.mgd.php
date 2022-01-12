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
];
