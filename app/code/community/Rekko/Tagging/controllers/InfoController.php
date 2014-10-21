<?php
class Rekko_Tagging_InfoController extends Mage_Adminhtml_Controller_Action {
  protected function _initAction() {
    $this->loadLayout()->_setActiveMenu( 'system/rekko' );
    return $this;
  }

  public function indexAction() {
    $this->_initAction()->renderLayout();
  }
}
?>