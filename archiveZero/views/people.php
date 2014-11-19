<?php
include 'lib/view.php';
include 'lib/tableView.php';

class PeopleView extends View {

	public function __construct($context) {
		parent::__construct($context);
	}

	public function prepare () {
		parent::prepare();
		
		
		$people=$this->getModel()->getPeople();
		$content="<table>\n".
		         "<tr><th>Name</th><th>Action</th></tr>\n";
		foreach ($people as $person) {
			$name=$person->getGivenName().' '.$person->getFamilyName();
			$action='&nbsp;<a href="##site##person/view/'.$person->getID().'">View</a>'.
					'&nbsp;<a href="##site##person/edit/'.$person->getID().'">Edit</a>'.
					'&nbsp;<a href="##site##person/delete/'.$person->getID().'">Delete</a>';
			$content.="<tr><td>$name</td><td>$action</td></tr>\n";
		}
		$content.="</table>\n";
		$content.='<p><a href="##site##person/new">Add a new person</a></p>';

		$this->addContent($content);	
	}
}
?>