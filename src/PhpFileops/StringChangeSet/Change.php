<?php

namespace PhpFileops\StringChangeSet;


class Change
{
	protected $offset;

	public function __construct($offset)
	{
		$this->offset = $offset;
	}

	public function getOffset()
	{
		return $this->offset;
	}

	public function setOffset($offset)
	{
		$this->offset = $offset;
	}
}

