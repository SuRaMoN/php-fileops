<?php

namespace PhpFileops;

use PHPUnit_Framework_TestCase;


class PhpSourceSearcherTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testFindNamespaceDefinition()
	{
		$phpSourceSearcher = new PhpSourceSearcher();
		$source = PhpTokenList::newFromFilePath(__FILE__);
		$this->assertEquals('namespace PhpFileops', $phpSourceSearcher->findNamespaceDefinition($source)->getTokenList()->getSource());
	}

	/** @test */
	public function testFindNamespaceName()
	{
		$phpSourceSearcher = new PhpSourceSearcher();
		$source = PhpTokenList::newFromSource('<?php namespace abc\def; ');
		$matchResult = $phpSourceSearcher->findNamespaceName($source);
		$this->assertEquals('abc\def', $matchResult->getTokenList()->getSource());
		$this->assertEquals(strlen('<?php namespace '), $matchResult->getSourceOffset());
		$this->assertEquals(strlen('abc\def'), $matchResult->getSourceLength());
	}

	/** @test */
	public function testFindClassUsages()
	{
		$phpSourceSearcher = new PhpSourceSearcher();
		$source = PhpTokenList::newFromSource('<?php new abc(); def::hij(); klm\nop::qrs(); new \tuv\hij();');
		$matchResults = $phpSourceSearcher->findClassUsages($source);
		$classNames = TokenMatch::arrayToSource($matchResults);
		$this->assertEquals(array('abc', '\tuv\hij', 'def', 'klm\nop'), $classNames);
	}
}

