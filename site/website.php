<?php
	include 'lib/interfaces.php';
	include 'lib/context.php';	
	include 'models/user.php';

	// version check 	
	if ((!function_exists('version_compare')) ||
	    (version_compare(PHP_VERSION, '5.3.0') < 0) ) {
		echo "This software requires PHP 5.3 or higher\n";
		exit;
	}

	try {
		// common stuff can go here
		date_default_timezone_set('Pacific/Auckland');
		set_error_handler("exception_error_handler");
		
		$context=Context::createFromConfigurationFile("website.conf");
		$context->setUser(new User($context));

		// getController will throw an exception if the request path is invalid
		// or the user is not authorised for access		
		$controller=getController($context);
		// the approach below is called "convention over configuration"
		// rather than configuring separate class names and routes, we base
		// the pattern on naming and site organisation conventions
		$controllerPath='controllers/'.strtolower($controller).'Controller.php';
		$controllerClass=$controller.'Controller';
		require $controllerPath;
		$actor = new $controllerClass($context);
		$actor->process();
		
	} catch (LoginException $ex) {
		echo 'TODO: please login or timeout message<br/>';
		
	} catch (Exception $ex) {
		logException ($ex);
		echo 'Page not found<br/>';
		// It is bad security practice to reveal exceptions to users
		// We log the exception and give a looser description of
		// the problem to the user.
		// We'll make the output prettier later
		exit;
	}
		
	function logException ($ex) {
		// do nothing for now
		print "Exceptiom thrown: {$ex->getMessage()}>br/>";
	}
	
	// This is a very simple router
	// We just match the URI to a stub of the controller name
	function getController($context) {
		$uri=$context->getURI();
		$path=$uri->getPart();
		switch ($path) {
			case 'admin':
				return getAdminController($context);
			case 'products':
				return 'ProductsViewer';
			case 'product':
				return 'ProductViewer';
			case 'cart':
				return 'Cart';
				
			case '':
				$uri->prependPart('home');
				return 'Static';
			case 'static':
				return 'Static';
			case 'login':
				return 'Login';
			case 'logout':
				return 'Logout';
			default:
				throw new InvalidRequestException ("No such page");
		}
	}

	// This is a very simple admin router
	// We match the next part of the URI to the controller name for the action
	function getAdminController($context) {
		if (!$context->getUser()->isAdmin() ) {
			throw new InvalidRequestException('Administrator access is required for this page');
		}
		$uri=$context->getURI();
		$path=$uri->getPart();
		switch ($path) {
		    case 'categories':
				return 'Categories';
		    case 'category':
				return 'Category';
		    case 'products':
				return 'Products';
		    case 'product':
				return 'Product';
			case 'people':
				return 'People';
			default:
				throw new InvalidRequestException ('No such page');
		}
	}
	

/*	
TODO: add this? 
 error_reporting(E_ALL | E_STRICT);
*/

/**
 * Convert errors, notices, warnings etc. into exceptions.
 */
	function exception_error_handler($errno, $errstr, $errfile, $errline ) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
?>