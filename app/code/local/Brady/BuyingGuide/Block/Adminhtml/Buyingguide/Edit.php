<?php

class Brady_BuyingGuide_Block_Adminhtml_Buyingguide_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	protected function _construct()
    {
        parent::_construct();
		$this->_objectId   = 'buyingguide_id';
        $this->_blockGroup = 'buyingguide';
        $this->_controller = 'adminhtml_buyingguide';
		$this->_headerText = 'Buying Guide Mass Upload';
    }	
	
	public function __construct()
    {
		parent::__construct();
        $this->removeButton('back')
			->removeButton('reset')
			->_updateButton('save', 'label', $this->__('Submit'))
			->_updateButton('save', 'id', 'upload_button')
			->_addButton('download_button', array(
				'label' => $this->__('Download Sample'),
				'onclick' => "setLocation('".$this->getUrl('*/*/downloadSample')."')"));
    }
}