<?php

/*
   A PHP framework for web sites by Mike Lopez
   
   Sample test controller
   ======================
   
   we'll work with a list of tests
   each test is defined by a file in the tests directory
*/
include 'lib/abstractController.php';
include 'models/static.php';
include 'views/static.php';

class StaticController extends AbstractController {
	private $path;
	public function __construct($context) {
		parent::__construct($context);
		$this->path=$context->getURI()->getRemainingParts();
	}
	
	protected function getView($isPostback) {
	
		$db=$this->getDB();
		$model = new StaticModel($db, $this->getContext(), $this->path);
		// create output
		$view=new StaticView($this->getContext());
	
		$view->setModel($model);
		$view->setTemplate('html/masterPage.html');
		$path=explode("/",$this->path);
		$title=ucwords(implode(' ',$path));
		$view->setTemplateField('pagename',$title);
		return $view;
	}
}
?>