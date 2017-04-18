<?php
class Augment_Notification_Model_Mysql4_Notify extends Mage_Core_Model_Mysql4_Abstract
{
     public function _construct()
     {
         $this->_init('notification/notify', 'id');
     }
}
