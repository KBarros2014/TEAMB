<?php

include 'lib/listSelectionView.php';

class ListSelectionViewTest extends UnitTest {
	private $lsv;
	private $unnamed;
	private $named;
	private $selected1;
	private $selected2;

	function __construct($context) {
		parent::__construct($context);
	}
	private function setup () {
		$data = array();
		$row=array();
		$row['id']=1;
		$row['value']='aaa';		
		$data[]=$row;
		$row=array();
		$row['id']=2;
		$row['value']='bbb';		
		$data[]=$row;
		$row=array();
		$row['id']=3;
		$row['value']='ccc';		
		$data[]=$row;
		$row=array();
		$row['id']=10;
		$row['value']='zzz';		
		$data[]=$row;
		$this->lsv = new ListSelectionView ($data);	
	
		$html='<select name="##name##">'.PHP_EOL.
			'<option value="1"##selected1##>aaa</option>'."\n".
			'<option value="2"##selected2##>bbb</option>'."\n".
			'<option value="3">ccc</option>'."\n".
			'<option value="10">zzz</option>'."\n".
			"</select>\n";
		
		$nosel=str_replace ('##selected1##','',$html);
		$nosel=str_replace ('##selected2##','',$nosel);		
		$this->unnamed=str_replace ('##name##','',$nosel);
		$this->named=str_replace ('##name##','formName',$nosel);
	
		$this->selected1=str_replace ('##name##','formName',$html);
		$this->selected1=str_replace ('##selected1##',' selected="selected"',$this->selected1);	
		$this->selected1=str_replace ('##selected2##','',$this->selected1);	

		$this->selected2=str_replace ('##name##','formName',$html);
		$this->selected2=str_replace ('##selected1##','',$this->selected2);	
		$this->selected2=str_replace ('##selected2##',' selected="selected"',$this->selected2);	
	}
	private function testDefault() {
		$this->expectException (new LogicException ("ID column must be specified"),array(&$this,'bad'));
	}
	protected function bad() {
		$test=$this->lsv->getHtml();
	}
	
	private function testBadId() {
		$this->lsv->setIdColumn('bad id');
		$this->expectException (new ErrorException ("Undefined index: bad id"),array(&$this,'bad'));
	}
	private function testBadValue() {
		$this->lsv->setIdColumn('id');
		$this->lsv->setValueColumn('badvalue');
		$this->expectException (new ErrorException ("Undefined index: badvalue"),array(&$this,'bad'));
	}	
	private function testUnnamed() {
		$this->lsv->setIdColumn('id');
		$this->lsv->setValueColumn('value');
		$this->assertEqual($this->lsv->getHtml(),$this->unnamed,"Unnamed list");
	}	
	private function testUnselected() {
		$this->lsv->setIdColumn('id');	
		$this->lsv->setValueColumn('value');
		$this->lsv->setFormName('formName');
		$this->assertEqual($this->lsv->getHtml(),$this->named,"Named list without selection");
	}
	private function testSelected1() {
		$this->lsv->setIdColumn('id');	
		$this->lsv->setValueColumn('value');
		$this->lsv->setFormName('formName');
		$this->lsv->setSelectedId('1');
		$this->assertEqual($this->lsv->getHtml(),$this->selected1,"Named list with id 1 selected");
	}
	private function testSelected2() {
		$this->lsv->setIdColumn('id');	
		$this->lsv->setValueColumn('value');
		$this->lsv->setFormName('formName');
		$this->lsv->setSelectedId('2');
		$this->assertEqual($this->lsv->getHtml(),$this->selected2,"Named list with id 2 selected");
	}
}

?>
