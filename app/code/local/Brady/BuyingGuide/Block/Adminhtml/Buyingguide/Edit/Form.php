<?php
class Brady_BuyingGuide_Block_Adminhtml_Buyingguide_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{      
		$form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/validate'),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ));
		$fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('buyingguide')->__('Buying Guide Mass Upload')));
				
		$fieldset->addField('import_file', 'file', array(
            'name'     => 'import_file',
            'label'    => 'Select CSV File to Import',
            'title'    => 'Select CSV File to Import',
            'required' => true
        ));
		$form->setUseContainer(true);
		$this->setForm($form);
		
		return parent::_prepareForm();
	}
}