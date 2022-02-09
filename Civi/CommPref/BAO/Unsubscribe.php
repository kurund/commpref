<?php

namespace Civi\CommPref\BAO;

class Unsubscribe {

  /**
   * Function alter menu items.
   *
   * @param array $items Menu items.
   *
   * @return void
   */
  public static function alterMenuMailingUnsubscribe(&$items) {
    $customUrl = \Civi::settings()->get('commpref_custom_unsubscribe');
    if ($customUrl) {
      $items['civicrm/mailing/unsubscribe']['page_callback'] = '\Civi\CommPref\BAO\Unsubscribe::::mailingUnsubscribeRedirect';
    }
  }

  /**
   * Function to redirect to custom url.
   *
   * @return void
   */
  public static function mailingUnsubscribeRedirect() {
    $job_id = \CRM_Utils_Request::retrieve('jid', 'Integer');
    $queue_id = \CRM_Utils_Request::retrieve('qid', 'Integer');
    $hash = \CRM_Utils_Request::retrieve('h', 'String');
    $q = \CRM_Mailing_Event_BAO_Queue::verify($job_id, $queue_id, $hash);
    if ($cid = $q->contact_id) {
      $customUrl = \Civi::settings()->get('commpref_custom_unsubscribe');
      $cs = \CRM_Contact_BAO_Contact_Utils::generateChecksum($cid);
      \CRM_Utils_System::redirect("/{$customUrl}?cid1={$cid}&cs={$cs}");
    }
  }

}
