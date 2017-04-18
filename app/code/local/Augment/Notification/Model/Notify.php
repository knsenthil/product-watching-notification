<?php
class Augment_Notification_Model_Notify extends Mage_Core_Model_Abstract
{
	 const NOTIFY_TYPE = "Notification Mail will update when Inventory Code, Revision Code, new Chili Document, inventory is back in stock and Sku changes";
     public function _construct()
     {
         parent::_construct();
         $this->_init('notification/notify');
     }
     
     public function getWatchDetails($product_id) {
		$collection = $this->getCollection()
					->addFieldToSelect('*')
					->addFieldToFilter('product_id',$product_id)
					->addFieldToFilter('user_id', $this->getCustomer()->getID());
		return $collection;
	}
	
	public function getWatchListByCustomer() {
			$collection = $this->getCollection()
					->addFieldToSelect('*')
					->addFieldToFilter('watch_status',1)
					->addFieldToFilter('user_id', $this->getCustomer()->getID());
		return $collection;
	}
	
	public function getWatchListByProduct($product_id) {
			$collection = $this->getCollection()
					->addFieldToSelect('*')
					->addFieldToFilter('watch_status',1)
					->addFieldToFilter('product_id', $product_id);
		return $collection;
	}
	
	private function getCustomer() {
		return Mage::getSingleton('customer/session')->getCustomer();
	}
}
