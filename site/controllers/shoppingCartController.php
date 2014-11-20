
<?php 

/*so lets get started with controller 
 based on ML controllers sample available on moodle
  the uri /patterns deal t int his controller
  /shoppingCart/new
 
 */
include 'lib/AbstractController.php';
include 'lib/view.php';
//include 'lib/tableView.php';
include 'models/product.php';
include 'models/shoppingCart.php';


 class ShoppingCartController extends AbstractController {
	private $context;
	private $subviewTemplate;
	public function __construct($context) {
		parent::__construct($context);
		$this->context=$context;
	}
     
	 
	public function handleDelete () {
		$cart = new ShoppingCart( $this->context);
	    $cart = $cart->delete();


	}	
	 
	protected function getView($isPostback) {
	  $uri=$this->getURI();
	  $action=$uri->getPart();
	  switch ($action) {
			case 'add':
			   return $this->handleAdd($isPostback,$uri->getID());	
			case '';
			
			case 'view':
				return $this->handleView($isPostback,$uri->getID());	
				
		    case 'delete':
				return $this->handleDelete($isPostback,$uri->getID());
			
			default:
				throw new InvalidRequestException ("Invalid action in URI");
		}	
		}
	  public function handleView ($postback, $id= null) {
	    echo "dd";
	     return 'html/productsViewerView.html';
	  
	  }
	public function  handleAdd($postback, $id =null){
	
	    if (!$postback) {
		//$this->subviewTemplate = $this->getTemplateForView();
			// subclass will call setfield for each field
           return $this->createView($id); 			// form with data from DB (or blank if ID null)		
		  
			}
			else {
			
			return $this->getFormData($id);
			
			}
			
		}


		protected function getFormData($id) {
		$quantitiy=$this->getInput('quantity');
		$this->createViewSave();
		$this->redirectTo("productsViewer");
		return null;
		}
		 public function createViewSave($id) {
	     //$db = $this->getDB();
	    // $view=new View($this->getContext());
		 $cart = new ShoppingCart( $this->context);
	     $cart = $cart->save();

		
		}

	  public function createView($id) {//allows user to view product selected
	     $db = $this->getDB();
	     $view=new View($this->getContext());
		 $product = new ProductModel ($db,$id);
		 $productPrice = $product->getProductPrice();
		 $this->productPrice= $product->getProductPrice();
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename',$this->getPagename());
		$view->setSubviewTemplate('html/shoppingCart.html');	
		$view->setSubviewField("name",$product->getProductName());
				$view->setSubviewField("price",$product->getProductPrice());
			$view->setSubviewField("$id",$id);

      return $view;
	   
	   
	   }
	protected function getPagename(){
		return  'Cart';
	
	}
	
		}
		