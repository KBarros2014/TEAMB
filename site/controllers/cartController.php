<?php

include 'lib/abstractController.php';

class CartController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}

	protected function getView($isPostback) {
		echo '<h1>This page is not yet implemented</h1><br/><br/>';
	}
}	
?>