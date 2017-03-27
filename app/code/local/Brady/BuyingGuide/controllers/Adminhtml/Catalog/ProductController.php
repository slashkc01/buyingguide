<?php
require_once('Mage/Adminhtml/controllers/Catalog/ProductController.php');

class Brady_BuyingGuide_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
	public function saveAction()
    {   
    	parent::saveAction();

    	$dataBuyingGuide = $this->getRequest()->getPost();
    	$productSku = $dataBuyingGuide['product']['sku'];
    	$productBuyingGuide = trim($dataBuyingGuide['product']['buying_guide']);
    	$encodeValue = htmlentities($productBuyingGuide);
    	$buyingGuideModel = Mage::getModel('buyingguide/buyingguide');
    	$productBuyingGuideId =  Mage::getModel('buyingguide/buyingguide')->getIdBySku($productSku);
    	$buyingGuideModel->load($productBuyingGuideId);
             
    	if(isset($buyingGuideModel['sku']) && $buyingGuideModel['sku']!=''){
    		//update existing data
	    	$data = array('buying_guide'=>$encodeValue);
			$buyingGuideModel->addData($data);			
			$buyingGuideModel->setSku($productSku)->save();			
    	}else{
    		//insert new data
            if ($encodeValue != '') {
        		$data = array('buying_guide'=>$encodeValue,'sku'=>$productSku);
    			$model = Mage::getModel('buyingguide/buyingguide')->setData($data);
    			$insertId = $model->save()->getId();
            }
    	}
    }
}