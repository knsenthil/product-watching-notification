<?php
class Augment_Notification_Block_Customer_Watchlist extends Augment_Notification_Block_Product_Watch {
	
	public function getWatchListByCustomer(){
		return $this->_getModel()->getWatchListByCustomer();
	}
}
