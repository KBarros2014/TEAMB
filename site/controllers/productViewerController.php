
<?php

include 'lib/abstractController.php';
include 'lIb/view.php';
class ProductViewerController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}

	protected function getView($isPostback) {
	   $db=$this->getDB();

		
	
		
		return $view;
	  
	}
}	
?>
