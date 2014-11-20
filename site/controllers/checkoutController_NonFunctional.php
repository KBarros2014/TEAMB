<?php
//include 'models/myShoppingCart.php';
include 'models/orderModel.php';
include 'lib/abstractController.php';

class CheckoutController extends AbstractController {
	private $context;
	
	public function __construct($context) {
		parent::__construct($context);
		$this->context=$context;
	}
	protected function getView($isPostback) {
		$cart = new ShoppingCart( $this->context);
		$cart->delete();
		$cart = new ShoppingCart( $this->context);
	
		$cart->addItem (new ShoppingCartItem(1,20,45.95));
		$cart->addItem (new ShoppingCartItem(1,10,45.95));
		
		$cart->setCustomerId(1); // tests data
		
		OrderModel::createFromCart($this->context->getDB(),$cart);
        $uri=$this->getURI();
		$action=$uri->getPart();
		switch ($action) {
			case 'blank':
				$this->subviewTemplate = $this->getTemplateForNew();
				return $this->handleBlank($isPostback);
			case 'delivery':
				$this->subviewTemplate = $this->getTemplateForEdit();
				return $this->handleEdit($isPostback,$uri->getID());	
			case 'payment':
				$this->subviewTemplate = $this->getTemplateForView();
				return $this->handleView($isPostback,$uri->getID());	
			case 'final':
				$this->subviewTemplate = $this->getTemplateForDelete();
				return $this->handleDelete($isPostback,$uri->getID());	
			default:
				throw new InvalidRequestException ("Invalid action in URI");
		}	
	}
    
    //
	public function handleBlank($postBack){
	    if (count($cart)==0) {
            $html='<p>There are no products in your cart</p>';
        }
        else
        {
            foreach ($cart as $item){
                /*
                $sql="select productID, productName, productPrice from products where ProductID =";
                $sql.=$item[0]
                $rows=$db->query($sql);
                */
                $html=$this->getHtml();
                $html = '<div class="DisplayedCart"><table>';
                foreach ($cart as $item){
                
                
                #Name and Price in textfield, quantity as input
                    if ($item[2] == 0){
                        removeItemAt($item);
                    }else{
                    $html.='<tr><td>'.$item[0].'</td><td>'.$item[1].'</td><td><input type="text" name="quantity" value="'.$item[2].'"></td>';
                    $html.='</tr>';
                    }
                $totalPrice = getTotalPrice();
                $html.='<tr>';
                $html.='<td>Current total is $'.$totalPrice;
                $html.='<td><input type ="submit" value="Update">';
                $html.='</tr>';
			
                $view= new View($this->getContext());
                $view->addContent($html);
                return $view;
                }
            }
        }
    }
}
	
?>