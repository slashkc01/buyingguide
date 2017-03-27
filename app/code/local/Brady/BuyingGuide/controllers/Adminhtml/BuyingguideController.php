<?php
class Brady_BuyingGuide_Adminhtml_BuyingguideController extends Mage_Adminhtml_Controller_action
{	
	protected function _isAllowed()
	{
		return true;
	}
	
	public function indexAction() {
		$this->_getSession()->addNotice(
            $this->__('Total size of uploadable files must not exceed 2MB. Please download the sample csv format for reference. ')
        );
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function downloadSampleAction() {
		$sampleData = array(array("sku","buyingguide"),
					array("SampleSku1","<p>content1</p><img src='test1.jpg'><a href='#'>test1</a>"),
					array("SampleSku2","<p>content2</p><img src='test2.jpg'><a href='#'>test2</a>"),
					array("SampleSku3","<p>content3</p><img src='test3.jpg'><a href='#'>test3</a>"));		
	    $csv="";
		foreach($sampleData as $r){
			$csv .= implode(",", $r).",\n";
		}
		
		$filename = "buying_guide_sample.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename); 

		echo $csv; 
		exit;
	}	
	
	public function getResultsAction() {
	    
		$myData = Mage::getSingleton('admin/session')->getBuyingGuideCsvData();		
	    $csv="";
		foreach($myData as $r){
			$csv .= implode(",", $r).",\n";
		}
		
		$filename = "buying_guide_results_".date('mdY_Hi', time()).".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename); 

		echo $csv; 
		exit;		
	}	
	
	public function validateAction()
	{	
		$time = date('Y-m-d');
		$data = $this->getRequest()->getPost();
		
		if ($data) {
			if(isset($_FILES['import_file']['name']) && $_FILES['import_file']['name'] != '')
			{
				try
				{      
					$path = Mage::getBaseDir().DS.'var'.DS.'urlcsv/csv/';  //destination directory 
					 
					$ext = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
					$fname = 'buyingguide_'.date('mdY_Hi', time()).'.'.$ext; //file name                       
					$uploader = new Varien_File_Uploader('import_file'); //load class
					$uploader->setAllowedExtensions(array('csv')); //Allowed extension for file
					$uploader->setAllowCreateFolders(true); //for creating the directory if not exists
					$uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
					$uploader->setFilesDispersion(false);
					$uploader->save($path, $fname); //save the file on the specified path
					
					$handle	= fopen($path.$fname, "r");
					
					$headers = array_flip(fgetcsv($handle, 4086, ","));
					
					$fp = fopen($path.'results.csv', 'w');
					fputcsv($fp, array('sku', 'buyingguide'));
					$count = 0;					 
					$err = false;
					$getCsvDataArray = array();
					while (($csv_data = fgetcsv($handle, 4086, ",")) !== FALSE) {						
						if(isset($csv_data[0]) && trim($csv_data[0])!=''){
							fputcsv($fp, $csv_data);
							$productSku = trim($csv_data[0]);
					    	$productBuyingGuide = trim($csv_data[1]);
					    	$encodeValue = htmlentities($productBuyingGuide);
					    	$buyingGuideModel = Mage::getModel('buyingguide/buyingguide');
					    	$productBuyingGuideId =  Mage::getModel('buyingguide/buyingguide')->getIdBySku($productSku);
					    	$buyingGuideModel->load($productBuyingGuideId);
					             
					    	if(isset($buyingGuideModel['sku']) && $buyingGuideModel['sku']!=''){
					    		//update existing data
						    	$data = array('buying_guide'=>$encodeValue);
								$buyingGuideModel->addData($data);			
								$buyingGuideModel->setSku($productSku)->save();
								$getCsvDataArray[] = $csv_data;
					    	}else{
					    		//insert new data
					            if ($encodeValue != '') {
					        		$data = array('buying_guide'=>$encodeValue,'sku'=>$productSku);
					    			$model = Mage::getModel('buyingguide/buyingguide')->setData($data);
					    			$insertId = $model->save()->getId();
					    			$getCsvDataArray[] = $csv_data;
					            }
					    	}
					    	
							$count++;
						}else{
							$count = 0;
						}						
					}
					if(count($getCsvDataArray)>0){
						$headerArray = array(array("sku","buyingguide"));
						Mage::getSingleton('admin/session')->setBuyingGuideCsvData(array_merge($headerArray,$getCsvDataArray)); 
					}
					if($count == 0)
						$this->_getSession()->addError($this->__('No data was imported.'));	
					elseif($err)
						$this->_getSession()->addError($this->__('There is an error with the import.'));	
					else
						$this->_getSession()->addSuccess($this->__('Import successfully Done. Click <a href="'.$this->getUrl('*/*/getResults').'">here</a> for results.'));					
				}
				catch (Exception $e)
				{
					$this->_getSession()->addError($e->getMessage());
				}
			}
			$this->_redirect('*/*/');
		} else {
			$this->_getSession()->addError($this->__('Data is invalid or file is not uploaded'));
			$this->_redirect('*/*/');
		}
	}
	
}