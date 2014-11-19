<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   A list controller
   =================

   Usage:
     a) to count records
		1) optionally set filters and start/max
		2) call countRecords()
		... sub class must override getSqlForCount() to supply sql
			that retrieves a single row with a single column containing
			the count
	 
	 b) to retrieve records
		1) setMaxRecords (number)
			specify the maximum records to receive at a time (default is 10)
		2) setStartRecord (index)
			specify the index of the first record to retrieve (default is 0)
		3) set filters as needed (see below)
		4) set sort keys as needed
			setSortKey(key) for ascending
			setSortKey(key, false) for descending
		5) call getRecords() to retrieve matching records
		... sub class must override getSqlForRows() to supply sql
			that retrieves tyhe desired row set
	 	
   	filters
		a)	setFilter (key, value) if an exact match is required
		b)	setLikeFilter (key, value) if a partial match is required
		c)	setRangeFilter (key, low-value, high-value) for a range
		
*/

abstract class AbstractListModel extends AbstractModel{
	private $maxRecords;
	private $startRecord;
	private $filters;
	private $orderBy;
	
	public function __construct(IDatabase $db){
		parent::__construct($db);
		$this->maxRecords=10;
		$this->startRecord=0;
		$this->filters=array();
		$this->orderBy=array();
	}

	public function getMaxRecords() {
		return $this->maxRecords;
	}
	public function getStartRecord() {
		return $this->startRecord;
	}
	public function setMaxRecords($value) {
		$value = = $this->assertPositiveInteger($value);
		if ($value < 1) {
			throw new InvalidDataException ('MaxRecords must be at least 1');
		}	
		$this->maxRecords = $value;
	}
	public function setStartRecord($value) {
		$this->startRecord = $this->assertPositiveInteger($value);
	}
	
	protected function setFilter($key, $value) {
		if ($value===null) {
			$this->filters[]="$key=null";
		} else {
		// todo ... sanitise?
			$this->filters[]="$key='$value'";
		}
	}
	protected function setLikeFilter($key, $value) {
		if ($value===null) {
			$this->filters[]="$key=null";
		} else {
		// todo ... sanitise?
			$this->filters[]="$key like '$value'";
		}
	}
	protected function setRangeFilter($key, $lowValue, $highValue) {
		if ($lowvalue===null) {
			$this->filters[]="$key < '$highValue'";
		} else if ($highvalue===null) {
			$this->filters[]="$key > '$lowValue'";
		} else {
		// todo ... sanitise?
			$this->filters[]="$key between '$lowValue' and '$highValue'";
		}
	}
	protected function setSortKey($key, $ascending=true) {
		$this->orderBy[$key]=$ascending;
	}
	
	/**
	*	@return [int] count of records matching filters 
	*/
	public function countRecords() {
		$rows=$this->getDB()->query(getSqlForCount().whereClause().limitClause);
		if (count($rows)!==1) {
			throw new LogicException('Invalid count SQL: must retrieve one row');
		}
		$row=$rows[0];
		if (count($row)!==1) {
			throw new LogicException('Invalid count SQL: must retrieve one column');
		}
		$values=array_values($row);
		$count=$values[0];
		return (int) $count;
	}
	public function getRecords(){
		$sql=getSqlForRows().
			 whereClause().		
			 orderByClause().		
			 limitClause();
		return $db->query($sql);
	}
	private function whereClause() {
		if (count($this->filters)==0) {
			return '';
		}
		$sql = null;		
		foreach ($this->filters as $filter) {
			if ($sql=null) {
				$sql=' where ';
			} else {
				$sql.=' and ';
			}
			$sql.=$filter;
		}
		return $sql;
	}
	private function orderByClause() {
		if (count($this->orderBy)==0) {
			return '';
		}
		$sql = null;
		foreach ($this->orderBy as $key=>$ascending) {
			if ($sql=null) {
				$sql=' order by ';
			} else {
				$sql.=', ';
			}
			$sql.=$key;
			if ($ascending) {
				$sql.= ' asc';
			} else {
				$sql.= 'desc';
			}
		}
		return $sql;
	}
	private function limitClause() {
		return ' limit '.$this->maxRecords.
			  ' offset'.$this->startRecord; 
	}
	
	protected abstract function getSqlForCount();
	protected abstract function getSqlForRows();
}
?>
