<?php
include 'lib/paginator.php';
class PaginatorTest extends UnitTest {
	
	function __construct($context) {
		parent::__construct($context);
	}
	
	private function testDefaults(){
		$pager = new Paginator();		
		$this->assertEqual ($pager->getRecordCount(),null,'default record count');
		$this->assertEqual ($pager->getStartRecord(),0,'default start record');
		$this->assertEqual ($pager->getRecordsPerPage(),10,'default records per page');
		$this->assertEqual ($pager->getMaxPages(),8,'default maximum pages');
		$this->assertEqual ($pager->getLinkRoot(),null,'default link root');
		$this->assertEqual ($pager->getHtml(),'','default html');
	}
	
	private function testSingle(){
		$pager = new Paginator();		
		$pager->setRecordCount(1);
		$this->assertEqual ($pager->getRecordCount(),1,'specified record count');
		$this->assertEqual ($pager->getStartRecord(),0,'default start record');
		$this->assertEqual ($pager->getRecordsPerPage(),10,'default records per page');
		$this->assertEqual ($pager->getMaxPages(),8,'default maximum pages');
		$this->assertEqual ($pager->getLinkRoot(),null,'default link root');
		$this->assertEqual ($pager->getHtml(),'<a class="selected" href="1-1_of_1">1</a>&nbsp;','Single record html');
	}
	private function testTwoPages(){
		$pager = new Paginator();		
		$pager->setRecordCount(24);
		$pager->setRecordsPerPage(12);
		$pager->setMaxPages(2);
		$this->assertEqual ($pager->getRecordCount(),24,'record count');
		$this->assertEqual ($pager->getStartRecord(),0,'default start record');
		$this->assertEqual ($pager->getRecordsPerPage(),12,'records per page');
		$this->assertEqual ($pager->getMaxPages(),2,'maximum pages');
		$this->assertEqual ($pager->getLinkRoot(),null,'default link root');
		$this->assertEqual ($pager->getHtml(),'<a class="selected" href="1-13_of_24">1</a>&nbsp;<a href="13-24_of_24">2</a>&nbsp;','html');
	
		// moving - record 11 should not change page
		$pager->setStartRecord(11);
		$this->assertEqual ($pager->getStartRecord(),11,'start record');
		$this->assertEqual ($pager->getHtml(),'<a class="selected" href="1-13_of_24">1</a>&nbsp;<a href="13-24_of_24">2</a>&nbsp;','html');
		
		// but moving to record 12 should change it
		$pager->setStartRecord(12);
		$this->assertEqual ($pager->getStartRecord(),12,'start record');
		$this->assertEqual ($pager->getHtml(),'<a href="1-13_of_24">1</a>&nbsp;<a class="selected" href="13-24_of_24">2</a>&nbsp;','html');
	}
	private function testManyPages(){
		$pager = new Paginator();		
		$pager->setRecordCount(1000);
		$pager->setRecordsPerPage(12);
		$pager->setMaxPages(10);
		$pager->setLinkRoot('#link#');
	
		$this->assertEqual ($pager->getRecordCount(),1000,'record count');
		$this->assertEqual ($pager->getStartRecord(),0,'default start record');
		$this->assertEqual ($pager->getRecordsPerPage(),12,'records per page');
		$this->assertEqual ($pager->getMaxPages(),10,'maximum pages');
		$this->assertEqual ($pager->getLinkRoot(),'#link#/','link root');
		$expected='<a class="selected" href="#link#/1-13_of_1000">1</a>&nbsp;'.
		          '<a href="#link#/13-25_of_1000">2</a>&nbsp;'.
				  '<a href="#link#/25-37_of_1000">3</a>&nbsp;'.
				  '<a href="#link#/37-49_of_1000">4</a>&nbsp;'.
				  '<a href="#link#/49-61_of_1000">5</a>&nbsp;'.
				  '<a href="#link#/61-73_of_1000">6</a>&nbsp;'.
				  '<a href="#link#/73-85_of_1000">7</a>&nbsp;'.
				  '<a href="#link#/85-97_of_1000">8</a>&nbsp;'.
				  '<a href="#link#/97-109_of_1000">9</a>&nbsp;'.
				  '<a href="#link#/109-121_of_1000">10</a>&nbsp;'.
				  '<a href="#link#/121-133_of_1000">&raquo;</a>&nbsp;';
		$this->assertEqual ($pager->getHtml(),$expected,'Many record html');
		
		// last record on last page of first block
		$pager->setStartRecord(119);
		$this->assertEqual ($pager->getStartRecord(),119,'start record');
		$expected='<a href="#link#/1-13_of_1000">1</a>&nbsp;'.
		          '<a href="#link#/13-25_of_1000">2</a>&nbsp;'.
				  '<a href="#link#/25-37_of_1000">3</a>&nbsp;'.
				  '<a href="#link#/37-49_of_1000">4</a>&nbsp;'.
				  '<a href="#link#/49-61_of_1000">5</a>&nbsp;'.
				  '<a href="#link#/61-73_of_1000">6</a>&nbsp;'.
				  '<a href="#link#/73-85_of_1000">7</a>&nbsp;'.
				  '<a href="#link#/85-97_of_1000">8</a>&nbsp;'.
				  '<a href="#link#/97-109_of_1000">9</a>&nbsp;'.
				  '<a class="selected" href="#link#/109-121_of_1000">10</a>&nbsp;'.
				  '<a href="#link#/121-133_of_1000">&raquo;</a>&nbsp;';
		$this->assertEqual ($pager->getHtml(),$expected,'html');

		// firstst record on first page of second block
		$pager->setStartRecord(120);
		$this->assertEqual ($pager->getStartRecord(),120,'start record');
		$expected='<a href="#link#/109-121_of_1000">&laquo;</a>&nbsp;'.
				  '<a class="selected" href="#link#/121-133_of_1000">11</a>&nbsp;'.
		          '<a href="#link#/133-145_of_1000">12</a>&nbsp;'.
				  '<a href="#link#/145-157_of_1000">13</a>&nbsp;'.
				  '<a href="#link#/157-169_of_1000">14</a>&nbsp;'.
				  '<a href="#link#/169-181_of_1000">15</a>&nbsp;'.
				  '<a href="#link#/181-193_of_1000">16</a>&nbsp;'.
				  '<a href="#link#/193-205_of_1000">17</a>&nbsp;'.
				  '<a href="#link#/205-217_of_1000">18</a>&nbsp;'.
				  '<a href="#link#/217-229_of_1000">19</a>&nbsp;'.
				  '<a href="#link#/229-241_of_1000">20</a>&nbsp;'.
				  '<a href="#link#/241-253_of_1000">&raquo;</a>&nbsp;';
		$this->assertEqual ($pager->getHtml(),$expected,'html');
	}
}
?>