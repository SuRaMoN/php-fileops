#!/usr/bin/env php
<?php

error_reporting(E_ALL | E_STRICT);

set_error_handler(function($errno, $errstr, $errfile, $errline) {
	if(error_reporting() != 0) {
		throw new Exception($errstr, $errno);
	}
});


$pharPath = __DIR__ . '/../build/php-fileops.phar';
$excludes = array(
	'/build/',
	'/tests/',
	'/composer.json',
	'/composer.lock',
	'/phpunit.xml.dist',
	'/.gitignore',
	'/tags',
	'/classes',
	'/Makefile',
	'/composer.phar',
	'/build-phar.php',
);

if(!file_exists(dirname($pharPath))) {
	mkdir(dirname($pharPath), 0755, true);
}

@unlink($pharPath);
$phar = new Phar($pharPath);

$headPathLength = strlen(realpath(__DIR__ . '/..')) + 1;

foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../')) as $file) {
	if(
		!$file->isFile()
		|| strpos($file->getRealPath(), DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR) !== false
		|| count(array_filter(array_map(function($exclude) use ($file) { return strpos($file->getRealPath(), $exclude) !== false; }, $excludes))) != 0
	) {
		continue;
	}
	$phar->addFile($file->getRealPath(), substr($file->getRealPath(), $headPathLength));
}

$phar->setStub("#!/usr/bin/env php\n" . $phar->createDefaultStub('bin/php-fileops.php'));
chmod($pharPath, 0775);

