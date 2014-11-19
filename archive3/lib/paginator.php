<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   A simple paginator
   ==================

   With large lists, we often want to present data page by page
   with only a limited number of records per page and an html
   paginator control that lets the user browse through the results.
   
   This class manages the paging process and can generate the control.
   
   Two main parameters control the logic.
   a) recordsPerPage specifies how many records are shown on a page
   b) maxPages specifies how many pages are shown in the paginator 
      control. When there are more pages than can be shown at a time,
	  next and previous links are created to support browsing.
	  
   Classes using this control should be prepared to supply a count of
   the total number of records in the set to be provided. This class 
   will supply the start record to be used. This is encoded in a user-
   readable token that is available for linking. The class can also 
   parse such tokens to set appropriate parameters.
   
   normal usage is as follows.
   a) if the controlling class does not have a token, it should create 
      a paginator, set records per page and max pages and set the record
	  count. The controlling class can then query the database with an 
	  offset of zero and a limit of records per page to get the initial 
	  list. an appropriate html paginator can be accessed by calling
	  getHtml();
	b) if the controlling class has a token, it should create a paginator
	   and then call the parseFromToken method. This will set the paginator 
	   data. The controlling class can call the getStartRecord() method to
	   determine the offset to use in database queries. The getHtml() method
	   will provide an appropriate paginator for display to the user.
	   
	In both cases, the controlling class should also provide a root for html
	links to be used in the paginator html.

*/

class Paginator {

	private $totalRecords;			// set by token or caller
	private $startRecord;			// set by token or caller
	private $recordsPerPage;		// set by caller
	private $maxPages;				// set by caller
	private $linkRoot;				// set by caller
	
	//	the following used by calcMetrics();
	private $firstPage;
	private $lastPage;
	private $selectedPage;
	private $totalPages;
	
	public function __construct() {
		$this->totalRecords=null;	// must be set somehow
		$this->startRecord=0;		// default = start with first
		$this->recordsPerPage=10;   // default
		$this->maxPages=8;   		// default	
		$this->linkRoot=null;
	}
	public function getRecordCount() {
		return $this->totalRecords;
	}
	public function getStartRecord() {
		return $this->startRecord;
	}
	public function getRecordsPerPage() {
		return $this->recordsPerPage;
	}
	public function getMaxPages() {
		return $this->maxPages;
	}
	public function getLinkRoot() {
		return $this->linkRoot;
	}
	public function setRecordCount($count) {
		$this->totalRecords = $count;
	}
	public function setStartRecord($record) {
		$this->startRecord = $record;
	}
	public function setRecordsPerPage($count) {
		if ($count < 1) {
			throw new LogicException ('Must have at least one record per page');
		}
		$this->recordsPerPage = $count;
	}
	public function setMaxPages($count) {
		if ($count < 1) {
			throw new LogicException ('Must have at least one page');
		}
		$this->maxPages = $count;
	}
	public function setLinkRoot ($html) {
		$this->linkRoot=$html.'/';
	}
	
	//  returns false or an array of the numeric values
	private function parseToken ($token) {
			
		// token is ss-ee_of_tt, ss= start, ee = end, tt = total	
		$result=array();
		
		// first part ss-
		$to = strpos($token,'-');
		if ($to===false) {
			return false;
		}
		$from = substr($token,0,$to);
		if (!ctype_digit($from)) {
			return false;
		}
		$from = (int)$from;
		$from--;
		if ($from < 0) {
			return false;
		}
		$result[] = $from;
		$rest = substr($token,$to+1);
	
		// second part ee_of_
		$of = strpos($rest,'_of_');
		if ($of===false) {
			return false;
		}
		$to = substr($rest,0,$of);
		if (!ctype_digit($to)) {
			return false;
		}
		$result[] = (int)$to;
		$rest = substr($rest,$of+4);
		
		// third part tt
		if (!ctype_digit($rest)) {
			return false;
		}
		$result[] = (int)$rest;	
		return $result;
	}
	public function isValidToken($token) {
		return $this->parseToken($token) !== false;
	}
	
	public function parseFromToken ($token) {
		$values = $this->parseToken($token);
		if ($values === false ){
			throw new InvalidRequestException ('Invalid URI paginator');
		}
		list ($from, $to, $total)=$values;
		$this->totalRecords = $total;
		$this->startRecord = $from;
	}
	public function getHtml() {
		$this->calcMetrics();
		$pages = $this->getPages();
		
		$html='';
		$selected = $this->selectedPage * $this->recordsPerPage;
		
		foreach ($pages as $page=>$start) {
			$link = $this->linkRoot.$this->getToken($start);
			if ($start==$selected) {
				$html.="<a class=\"selected\" href=\"$link\">$page</a>&nbsp;";
			} else {
				$html.="<a href=\"$link\">$page</a>&nbsp;";
			}
		}
		return $html;
	}
	
	private function calcMetrics() {
		$this->totalPages=ceil($this->totalRecords / $this->recordsPerPage);
		$this->selectedPage = floor($this->startRecord / $this->recordsPerPage); // zero based
		$blockNumber = floor($this->selectedPage/ $this->maxPages);
		$this->firstPage = $blockNumber * $this->maxPages;		// the first page to show
		$this->lastPage = $this->firstPage + $this->maxPages -1; // the last page to show
		while ($this->lastPage >= $this->totalPages) {
			$this->lastPage--;
		}
	// for debugging ...
		// echo 'totalrecords is '.$this->totalRecords .'<br/>';
		// echo 'first page is '.$this->firstPage.'<br/>';
		// echo 'last page is '.$this->lastPage.'<br/>';
		// echo 'selected page is '.$this->selectedPage.'<br/>';
		// echo 'total pages is '.$this->totalPages.'<br/>';
	}
	/**		creates an array mapping page numbers (or text) to start pages
	*
	*/
	private function getPages() {
//		$this->calcMetrics();
		$start = $this->firstPage * $this->recordsPerPage;
		$pages=array();
		if ($this->firstPage > 0) {
			$pages['&laquo;']=$start - $this->recordsPerPage;
		}
		$pageNo=$this->firstPage;
		while ($pageNo <= $this->lastPage) {
			$pageNo++;
			$pages[''.$pageNo]= $start;
			$start += $this->recordsPerPage;
		}
		if ($pageNo < $this->totalPages) {
			$pages['&raquo;']=$start;
		}
		return $pages;
	}

	public function getToken($start) {
		$start = $start + 1;
		$end = $start + $this->recordsPerPage;
		if ($end > $this->totalRecords) {
			$end = $this->totalRecords;
		}
		return "{$start}-{$end}_of_{$this->totalRecords}";
	}
}
?>