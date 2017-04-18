<?php
class Augment_Notification_Block_Product_Watch extends Mage_Core_Block_Template {
	
	public function getWatchDetails($product_id) {
		return $this->_getModel()->getWatchDetails($product_id);
	}
	
	protected function _getModel(){
		return Mage::getModel('notification/notify');
	}
	
	public function getProductName($product_id) {
		return $product = Mage::getModel('catalog/product')->load($product_id);
		
	}
	
}
