<?php

namespace PhpFileops\StringChangeSet;


class Insertion extends Change
{
	protected $insertion;

	public function __construct($offset, $insertion)
	{
		parent::__construct($offset);
		$this->insertion = $insertion;
	}

 	public function getInsertion()
 	{
 		return $this->insertion;
 	}
}

