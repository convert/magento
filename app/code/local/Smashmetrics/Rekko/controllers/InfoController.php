<?php

class Smashmetrics_Rekko_InfoController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu( 'system/rekko' );

        return $this;
    }

    public function indexAction() {
//        $this->loadLayout()
//                ->_setActiveMenu('system/rekko');
//                 $this->loadLayout();
//        $block = $this->getLayout()
//        ->createBlock('core/text', 'example-block')
//        ->setText('<h1>This is a text block</h1>');
//        $this->_addContent($block);
//        $this->renderLayout();
       $this->_initAction()
                ->renderLayout();
    }


}



