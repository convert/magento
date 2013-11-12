<?php

/*
 * Copyright (C) 2012 Clearspring Technologies, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<?php
session_start();
class Smashmetrics_Rekko_Model_Observer {

    public function addRekkoCode($observer) {
        $block = $observer->getBlock();
        $transport = $observer->getTransport();
    }
 
	public function newsletterSubscriberSave(Varien_Event_Observer $observer) {

	    $subscriber = $observer->getEvent()->getSubscriber();
	    $email = $subscriber->getEmail();
		Mage::getSingleton('core/session')->setNewsSubscriber($email);
	    return $this;
	}
	public function customerRegisterSuccess(Varien_Event_Observer $observer)
    {
        $email = $observer->getEvent()->getCustomer()->getEmail();
        Mage::getSingleton('core/session')->setNewUser($email);
	    return $this;
    }
}