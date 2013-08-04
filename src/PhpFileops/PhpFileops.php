<?php

namespace PhpFileops;


class PhpFileops
{
	public function __construct()
	{
	}

	public function renameNamespace($file, $from, $to)
	{
		$namespaceRenamer = new NamespaceRenamer($file, $from, $to);
		file_put_contents($file, $namespaceRenamer->rename());
	}
}

