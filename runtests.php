<?php
	include "lib/interfaces.php";
	include 'lib/context.php';
	include 'controllers/testController.php';	
	include 'models/user.php';
	try {
		date_default_timezone_set('Pacific/Auckland');
		set_error_handler("exception_error_handler");
		$context=Context::createFromConfigurationFile("website.conf");
		$context->setUser(new User($context));
		$tests = new TestController($context);
		$tests->process();
	} catch (Exception $ex) {
		echo $ex->getMessage();
	}
	function exception_error_handler($errno, $errstr, $errfile, $errline ) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
?>