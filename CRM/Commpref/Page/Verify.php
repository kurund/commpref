<?php
use CRM_Commpref_ExtensionUtil as E;

class CRM_Commpref_Page_Verify extends CRM_Core_Page {

  public function run() {
    CRM_Utils_System::setTitle(E::ts('Email Verification'));

    $verify = FALSE;

    // get params from the url
    $contactId = CRM_Utils_Request::retrieve('cid', 'Positive', $this, TRUE);
    $email = CRM_Utils_Request::retrieve('e', 'String', $this, TRUE);
    $contactChecksum = CRM_Utils_Request::retrieve('cs', 'String', $this, TRUE);

    // check if contact and checksum is valid
    if (CRM_Contact_BAO_Contact_Utils::validChecksum($contactId, $contactChecksum)) {
      // check and verify contact email
      $verify = \Civi\CommPref\BAO\Email::verifyContactEmail($contactId, $email);
    }

    $this->assign('verify', $verify);

    parent::run();
  }

}
