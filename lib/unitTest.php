<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   A simple unit test case
   =======================
	
*/

class UnitTest {
	private $context;
	private $errors;
	private $asserts;
	private $failures;
	private $currentTestMethod;

	function __construct($context) {
		$this->context=$context;
		$this->errors=null;
		$this->asserts=0;
		$this->failures=0;
	}
	function getContext() {
		return $this->context;
	}
	function getResults() {
		$this->beginTests();
		$this->doTests();
		$this->doAll();
		$this->endTests();
		return $this->errors;
	}
	function getAsserts() {
		return $this->asserts;
	}
	function getFailures() {
		return $this->failures;
	}
	protected function beginTests() {
	}
	protected function doTests() {
//		throw new Exception ('Unit test not implemented');
// this is now optional
	}
	protected function endTests() {
	}
	
	protected function doAll() {
		$reflector = new ReflectionClass($this);
		$methods = $reflector->getMethods();
		$setup = null;
		$teardown = null;
		$tests=array();
		
		// identify all test methods in sub-classes
		foreach ($methods as $method) {
			$m=$method->name;
			$c=$method->class;
			if ($c!=="UnitTest") {
				if ($m=='setup') {
					$setup=$method;
				} else if ($m=='teardown') {
					$teardown=$method;
				} else if (substr($m,0,4)=='test') {
					$tests[]=$method;
				}
			}
		}	
	
		// call all test methods
		foreach ($tests as $test) {		
			
			$this->currentTestMethod = 
				'Method: '.$test->name.
				'(), lines '.$test->getStartLine().
				' to '.$test->getEndLine().
				' in '.$test->getFileName();
				
			if ($setup !== null) {
				$this->execute ($setup);
			}
			$this->execute ($test);
			if ($teardown !== null) {
				$this->execute ($teardown);
			}
		}
	}

// TODO: consider adding $docComment annotations = $method->getDocComment();
	private function execute($method) {
		try {
			$method->setAccessible(true);		// allow calls to private methods
			$method->invoke($this);
		} catch (exception $ex) {
			$msg = 'Unexpected exception in method '.$method->name .
				   '; exception class is '.get_class($ex).
				   ', with message: '.$ex->getMessage();
			$this->setError($msg);
		}
	}
	
	protected function setError($message) {
		if ($this->currentTestMethod !==null) {
			$message ='<span title="'.$this->currentTestMethod.'"> '.
						$message.'</span>';
		}
		if ($this->errors==null) {
			$this->errors=$message;
		} else {
			$this->errors.='<br/>'.$message;
		}
		$this->failures++;
	}
	protected function assert($test,$message) {
		$this->asserts++;
		if (!$test) {
			$this->setError ($message);
		}
	}
	
	protected function assertFalse($test,$message) {
		$this->asserts++;
		if ($test) {
			$this->setError ($message);
		}
	}
	protected function assertEqual($first, $second, $message) {
		$this->asserts++;
		if ($first!=$second) {
			$message.=", expecting '$second', found: '$first'";
			$this->setError ($message);
		}
	}
	
	protected function expectException ($exception, $function) {
		try {
			$this->asserts++;
			if (is_callable($function)) {
				call_user_func($function);
				$this->setError ('Test did not throw expected exception:'.$exception->getMessage());
			} else {
				$this->setError ('Supplied function is not callable');
			}
		} catch (Exception $ex) {
			$this->assertEqual(get_class($ex),get_class($exception),'Unexpected exception type raised');
			$this->asserts--;
			$this->assertEqual($ex->getMessage(),$exception->getMessage(),'Unexpected exception text');
			$this->asserts--;
		}
	}
}
?>