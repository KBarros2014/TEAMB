<?php
	include_once 'lib/abstractModel.php';
	abstract class AbstractEntityModel extends AbstractModel{
	
		private $id;

		public function __construct(IDatabase $db, $id){
			parent::__construct($db);
			
			$this->id=$id;
			$this->init();
			if ($id !== null) {
				if (!is_int($id) && !ctype_digit($id)) {
					throw new InvalidDataException("Invalid database ID ($id)");
				}
				$sql=$this->getLoadSql($id);
				$rows=$this->getDB()->query($sql);
				if (count($rows)!==1) {
					throw new InvalidDataException("ID ($id) not found");
				}
				$row=$rows[0];
				$this->loadData($row);
			}
		}

		public function getID() {
			return $this->id;
		}
		protected function setID($newID) {
			$this->id=$newID;
			$this->didChange();
		}

		public function save() {
			if ($this->hasChanges()) {
				if (!$this->allRequiredFieldsArePresent()) {
					throw new InvalidDataException("Attempt to save incomplete data");	
				}
				$db=$this->getDB();
				if ($this->id === null) {
					if ($db->execute($this->getInsertionSql()) !== 1) {
						throw new InvalidDataException('Insert failed');	
					}
					$this->setId($db->getInsertID());	
					$this->didChange(false);
					return true;
				} else {
					$sql=$this->getUpdateSql();
					$rowsAffected=$db->execute($sql);
					
					switch ($rowsAffected) {
						case 0:
							$this->didChange(false);
							return false;
						case 1:
							$this->didChange(false);
							return true;
						default:
							throw new InvalidDataException('Update failed for id '.$this->id);	
					}				}
			} 
			return false;
		}	
		public function delete () {
			if ($this->id===null) {
				throw new LogicException('Cannot delete record with null id');
			}	
			if ($this->getDB()->execute($this->getDeletionSql()) !== 1) {
				throw new LogicException('Deletion failed for id '.$this->id);
			}
			$this->init();
			$this->setId(null);
			$this->didChange(false);
		}
		
		// must overrides
		protected abstract function init();
		protected abstract function loadData($row);
		protected abstract function allRequiredFieldsArePresent();
		protected abstract function getLoadSql($id);
		protected abstract function getInsertionSql();
		protected abstract function getUpdateSql();	
		protected abstract function getDeletionSql();
	}
?>