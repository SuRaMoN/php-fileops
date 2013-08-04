<?php

namespace PhpFileops\StringChangeSet;


class Deletion extends Change
{
	protected $length;

	public function __construct($offset, $length)
	{
		parent::__construct($offset);
		$this->length = $length;
	}
 
	public function getLength()
	{
		return $this->length;
	}
}

