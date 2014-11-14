<?php
include 'lib/view.php';

class TestsView extends view {

	public function __construct($context) {
		parent::__construct($context);
	}

	public function prepare () {
		$tests=$this->getModel()->getTests();
		$numTests=0;
		$numOK=0;
		$numAsserts=0;
		$numFailures=0;
		$totalTime = 0;
		$content="<table>\n".
		         "<tr><th>Test Name</th><th>Author</th><th>Outcome</th><th>Asserts</th><th>Failures</th><th>Time taken</th></tr>\n";
		foreach ($tests as $test) {
			$name=$test->getName();
			$author=$test->getAuthor();
			$outcome=$test->getOutcome();
			$asserts=$test->getAsserts();
			$failures=$test->getFailures();
			$time=$test->getTimeTaken() * 1000;	// millisecs
			$totalTime +=$time;
			$time=number_format ($time,1) ;	
			$numTests++;
			$numAsserts+=$asserts;
			$numFailures+=$failures;
			if ($outcome===null) {
				$outcome='<span style="color:#070;">OK</span>';
				$numOK++;
			} else {
				$outcome='<span style="color:red;">'.$outcome.'</span>';
			}			
			$content.="<tr><td>$name</td><td>$author</td><td>$outcome</td>".
			              "<td class=\"r\">$asserts&nbsp;</td>".
						  "<td class=\"r\">$failures&nbsp;</td>".
						  "<td class=\"r\">$time ms&nbsp;</td></tr>\n";
		}
		$content.="</table>\n";
		$numErrors=$numTests-$numOK;
		$totalTime=number_format($totalTime,0) ;	
		$content.="<p>$numTests tests were run; $numOK were OK and ".
		           "$numErrors failed;".
				   " $numFailures of $numAsserts assertions failed. The total time taken was $totalTime ms.</p>";

		$this->addContent($content);
	}
}
?>