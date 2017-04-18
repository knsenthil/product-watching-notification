<?php
class Augment_Notification_Model_Observer {
    
    public function detectProductAttributeChanges($observer) {
       /**
         * @var $product Mage_Catalog_Model_Product
         * @var $user    Mage_Admin_Model_User
         */
        $product = $observer->getEvent()->getProduct();
        if ($product->hasDataChanges()) {
            try {
                $user       = Mage::getSingleton('admin/session')->getUser();
                $attributes = array(
                    'sku',
                    'name'
                );
                $new        = array();
                $org        = array();
                $changes    = array();
                foreach ($attributes as $attribute) {
                    if (!is_array($product->getData($attribute))) {
                        $new[$attribute] = ($product->getData($attribute)) ? $product->getData($attribute) : null;
                      
                        if (!is_array($product->getOrigData($attribute))) {
                            $org[$attribute] = ($product->getOrigData($attribute)) ? $product->getOrigData($attribute) : null;
                            if (($new[$attribute] != $org[$attribute])) {
                                $changes[$attribute] = array('new'=> $new[$attribute],
                                                             'old'=> $org[$attribute],'attribute'=>$attribute);
								$changed_attribute[] = $attribute;
                            }
                        }
                    }
                }
                if(array_count_values($changed_attribute)) {
					$customers = Mage::getModel('notification/notify')->getWatchListByProduct($product->getId());
					foreach($customers as $_customer) {
						$customerData = Mage::getModel('customer/customer')->load($_customer->getUserId());
						$recipients[$customerData->getEmail()] = $customerData->getFirstname();
					}
					$this->successmail($changed_attribute,$product,$recipients);
				}
               
            } catch (Exception $e) {
                Mage::log($e->getTraceAsString(), null, 'product_changes_fault.log');
            }
        }
        return $this;
	}
	
	  public function successmail($changed_attributes,$product,$recipients) {
		$templateId = 'product_attribute_changes_notification';
		$sender = array(
			'name' => Mage::getStoreConfig('trans_email/ident_general/name'),
			'email' => Mage::getStoreConfig('trans_email/ident_general/email')
		);
		$store = Mage::app()->getStore();
		$var = array(
					'attribute'=>implode(",",$changed_attributes),
					'product_name'=>$product->getName(),
					'product_url'=>$product->getProductUrl()
				);
		$translate = Mage::getSingleton('core/translate');
		Mage::getModel('core/email_template')->sendTransactional(
			$templateId,
			$sender,
			array_keys($recipients),
			array_values($recipients),
			$var,
			$store->getId()
		);
	}
}
?>
