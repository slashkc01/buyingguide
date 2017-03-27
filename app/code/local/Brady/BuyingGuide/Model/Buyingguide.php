<?php

class Brady_BuyingGuide_Model_Buyingguide extends Mage_Core_Model_Abstract 
{
    protected function _construct()
    {
        $this->_init('buyingguide/buyingguide');
    }

    public function getIdBySku($sku)
    {     
		$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('core_read');
	
		$select = $read->select('id')
                ->from('buying_guide')
                ->where('sku =(?)', $sku);
           
		return $read->fetchOne($select);	
    }

    public function getSkuBuyingGuide($sku)
    {     
		$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('core_read');
	
		$select = $read->select('*')
                ->from('buying_guide')
                ->where('sku =(?)', $sku);
           
		return $read->fetchAll($select);	
    }
}