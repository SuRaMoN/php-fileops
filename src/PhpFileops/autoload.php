<?php

spl_autoload_register(function ($className) {
	$availableClasses = array(
		'PhpFileops\NamespaceRenamer',
		'PhpFileops\PhpFileops',
		'PhpFileops\PhpSourceSearcher',
		'PhpFileops\PhpTokenList',
		'PhpFileops\TokenMatch',

		'PhpFileops\StringChangeSet\Change',
		'PhpFileops\StringChangeSet\Deletion',
		'PhpFileops\StringChangeSet\Insertion',
		'PhpFileops\StringChangeSet\StringChangeSet',
	);
	if(in_array($className, $availableClasses)) {
		require(__DIR__ . '/../' . strtr($className, '\\', '/') . '.php');
	}
});

