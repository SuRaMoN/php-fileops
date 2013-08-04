<?php

require_once(__DIR__ . '/../src/autoload.php');

error_reporting(E_ALL | E_STRICT);

set_error_handler(function($errno, $errstr, $errfile, $errline) {
	if(error_reporting() != 0) {
		throw new Exception($errstr, $errno);
	}
});


if(php_sapi_name() != 'cli') {
	die('php-fileops should be called from the command line only, eg: php php-fileops mv NamespaceFrom NamespaceTo');
}


$phpFileOps = new PhpFileops\PhpFileops();


