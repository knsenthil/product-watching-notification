<?php
class Augment_Notification_IndexController extends Mage_Core_Controller_Front_Action {
	
	public function indexAction() {
		$data = $this->getRequest()->getPost(); 
		$collection = $this->getModel()->getWatchDetails($data['product_id']);
		if($collection->getSize()) {
			$collection = $collection->getFirstItem();
			$model = $this->getModel()->load($collection['id']);
			$model->setWatchStatus((int)!$data['watch']);
			$model->save();
			$status = ((int)!$data['watch']==0)?"Removed":"Added";
			$result = array('msg'=>"Product Successfully $status in Watchlist",'value'=>(int)!$data['watch']);
			echo json_encode($result); exit;
			
		} else {
			$values = array('user_id'=>$this->_customer()->getId(),'product_id'=>$data['product_id'],'watch_status'=>!$data['watch']); 
			$model = $this->getModel()->setData($values);
			$model->save();
			$result = array('msg'=>"Product Successfully Added in Watchlist",'value'=>(int)!$data['watch']);
			echo json_encode($result); exit;
		}
		
	}
	
	protected function _customer() {
		 return $customerData = Mage::getSingleton('customer/session')->getCustomer();
	}
	
	private function getModel() {
		return Mage::getModel('notification/notify');
	}
	
	public function watchlistAction() {
		if (!Mage::getSingleton('customer/session')->isLoggedIn()):
            $this->_redirect('customer/account/login');
            return;
        endif;
		$this->loadLayout();
        $this->renderLayout();
	}
}
