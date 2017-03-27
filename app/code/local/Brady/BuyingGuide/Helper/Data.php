<?php
class Brady_BuyingGuide_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	/**
	* Code to retrieve buying_guide value
	*/
	public function getBuyingGuideValue($sku)
	{		
    	$productBuyingGuide =  Mage::getModel('buyingguide/buyingguide')->getSkuBuyingGuide($sku);    	
		return $productBuyingGuide;			
	}
}
