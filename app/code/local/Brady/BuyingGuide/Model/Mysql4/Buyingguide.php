<?php

class Brady_BuyingGuide_Model_Mysql4_Buyingguide extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {    	
        $this->_init('buyingguide/buyingguide', 'id');
    }
}