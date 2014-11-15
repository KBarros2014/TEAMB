<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   Sample CRUD controller for a product 
   ====================================

   The following URI patterns are handled by this controller: 
   
   /admin/product/new         	create a new product
   /admin/product/edit/nn		edit product nn
   /admin/product/delete/nn		delete product nn
   /admin/product/view/nn       view product nn
   /admin/product/images/nn     view or upload images for product nn
   
   (nn is the product ID)
   
   Note that most of the logic is in the parent CRUD controller
   Here, We're just implementing the product specific stuff
*/

include 'controllers/crudController.php';
include 'models/product.php';
include 'models/productImageUploader.php';
include 'lib/listSelectionView.php';

class ProductController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);
	}
	protected function getPagename(){
		return 'Products';
	}
	// the following methods are the must-overrides in the Crud controller
	protected function getTemplateForNew () {
		return 'html/forms/adminProductNew.html';
	}
	protected function getTemplateForEdit () {
		return 'html/forms/adminProductEdit.html';
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminProductDelete.html';
	}
	protected function getTemplateForView () {
		return 'html/forms/adminProductView.html';
	}
	protected function createModel($id) {
		return new ProductModel($this->getDB(),$id);
	}
	protected function getModelData($model) {
		$this->setField('name', $model->getName());
		$this->setField('description',$model->getDescription());	
		$this->setField('price',$model->getPrice());	
		$this->setField('category',$model->getCategory()->getName().' ('.$model->getCategoryID().')');	
		$this->setField('categoryList',$this->getCategoryList($model->getCategoryID()));
		$this->setField('thumbnail',$model->getThumbnail());	
	}
	protected function getFormData() {
		$name=$this->getInput('name');
		$description=$this->getInput('description');
		$price=$this->getInput('price');
		$categoryID=$this->getInput('categoryID');

		$this->setField('name', $name);
		$this->setField('description', $description);
		$this->setField('price', $price);
		$this->setField('category', $categoryID);
		
		$error=ProductModel::errorInName($name);
		if ($error!==null) {
			$this->setError ('name',$error);
		}
		$error=ProductModel::errorInDescription($description);
		if ($error!==null) {
			$this->setError ('description',$error);
		}
		$error=ProductModel::errorInPrice($price);
		if ($error!==null) {
			$this->setError ('price',$error);
		}
		$error=ProductModel::errorInCategoryID($this->getDB(), $categoryID);
		if ($error!==null) {
			$this->setError ('category',$error);
		}
		$this->setField('categoryList', $this->getCategoryList($categoryID));
	}
	protected function updateModel($model) {
		$name=$this->getField('name');
		$description=$this->getField('description');
		$price=$this->getField('price');
		$categoryID=$this->getField('category');
		$model->setName($name);
		$model->setDescription($description);	
		$model->setPrice($price);	
		$model->setCategoryID($categoryID);	
		echo "saving model<br/>";
		if ($model->save()) {
			$this->redirectTo('admin/products',"Product '$name' has been saved");
		} else {
			$this->redirectTo('admin/products');
		}
	}
	protected function deleteModel($model) {
		$name=$model->getName();
		$thumbnail =$model->getThumbnail();
		$image=$model->getImage();
		$model->delete();
		if (file_exists ($thumbnail)) {
			unlink ($thumbnail);
		}
		if  (file_exists ($image)) {
			unlink ($image);
		}
		$this->redirectTo('admin/products',"Product '$name' has been deleted");
	}	
	private function getCategoryList($selectedID) {
		$db=$this->getDB();
		$sql='select categoryID, name from categories order by name';
		$rowset=$db->query($sql);
		$list = new ListSelectionView($rowset);
		$list->setFormName ('categoryID');
		$list->setIdColumn ('categoryID');
		$list->setValueColumn ('name');
		$list->setSelectedId ($selectedID);	
		return $list->getHtml();
	}
	protected function extraActions ($isPostback, $action) {
		switch ($action) {
			case 'images':
				return $this->handleImages ($isPostback);
			default:
				parent::extraActions($isPostback, $action);
		}
	}
	private function handleImages ($isPostback) {	
		$this->subviewTemplate = 'html/forms/adminProductImages.html';
		$id=$this->getURI()->getID();
		$uploadError=null;	
		if ($isPostback) {
			try {
				$uploader = new ProductImageUploader('picture',$id);
				$uploader->upload();
			} catch (Exception $ex) {
				$uploadError=$ex->getMessage();
			}
		}
		$model=$this->createModel($id);	
		$this->setField('name', $model->getName());
		$this->setField('description',$model->getDescription());	
		$this->setField('thumbnail',$model->getThumbnail());	
		$this->setField('image',$model->getImage());	
		if ($uploadError !==null) {
			$this->setError('image','Error: '.$uploadError);	
		}		
		$view= $this->createView($id); 	
		return $view;
	}
}
?>