<?php

class ProductTestData extends UnitTest {
	
	function __construct($context) {
		parent::__construct($context);
	}

	protected function doTests() {
		$context=$this->getContext();
		$db=$context->getDB();
		$sql=array();
		for ($i=2 ; $i < 1000; $i++ ) {
			$name="Product name $i";
			$description="Product description $i";
			$price=$i * 1.01;
			$category=1;
			$sql[] = "insert into products (name, description, price, categoryID) ".
			         "values ('$name', '$description', $price, $category)";
		}
		$db->executeBatch($sql);
	}
}
?>
	