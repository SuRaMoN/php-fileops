<?php

namespace PhpFileops;

use PHPUnit_Framework_TestCase;


class NamespaceRenamerTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testSimpleNamespaceRename()
	{
		$renamer = new NamespaceRenamer(__FILE__, 'PhpFileops', 'PhpFileops2');
		$expected = preg_replace('/PhpFileops/', 'PhpFileops2', file_get_contents(__FILE__), 1);
		$this->assertEquals($expected, $renamer->rename());
	}

	/** @test */
	public function testNoNamespaceFound()
	{
		$renamer = new NamespaceRenamer(__FILE__, 'UnknownNamespace', 'NewUnknownNamespace');
		$this->assertEquals(file_get_contents(__FILE__), $renamer->rename());
	}
}

