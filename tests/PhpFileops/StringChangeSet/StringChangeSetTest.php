<?php

namespace PhpFileops\StringChangeSet;

use PHPUnit_Framework_TestCase;


class StringChangeSetTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testAddReplace()
	{
		$changeSet = new StringChangeSet('1269');
		$changeSet->addReplace('2', '2345', 1);
		$changeSet->addReplace('6', '678', 2);
		$this->assertEquals('123456789', $changeSet->apply()->getString());
	}

	/** @test */
	public function testAddReplaceBorderCases()
	{
		$changeSet = new StringChangeSet('123');
		$changeSet->addReplace('3', '4', 2);
		$changeSet->addReplace('1', '12', 0);
		$changeSet->addReplace('2', '3', 1);
		$this->assertEquals('1234', $changeSet->apply()->getString());
	}
}

