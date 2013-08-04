<?php

namespace PhpFileops\StringChangeSet;

use InvalidArgumentException;
use SplQueue;


class StringChangeSet
{
	protected $target;
	protected $changes;

	public function __construct($target)
	{
		$this->target = $target;
		$this->changes = new SplQueue();
	}

	public function addReplace($from, $to, $offset)
	{
		if(substr($this->target, $offset, strlen($from)) != $from) {
			throw new InvalidArgumentException('From-replacement does not fit in given offset');
		}
		$this->changes[] = new Deletion($offset, strlen($from));
		$this->changes[] = new Insertion($offset, $to);
		return $this;
	}

	public function getString()
	{
		return $this->target;
	}

	public function apply()
	{
		while(!$this->changes->isEmpty()) {
			$this->applyChange($this->changes->shift());
		}
		return $this;
	}

	protected function applyChange(Change $change)
	{
		switch(true) {
			case $change instanceof Deletion:
				return $this->applyDeletion($change);
			case $change instanceof Insertion:
				return $this->applyInsertion($change);
			default:
				throw new InvalidArgumentException('Unknown change: ' . get_class($change));
		}
	}

	protected function applyDeletion(Deletion $deletion)
	{
		$this->target = substr_replace($this->target, '', $deletion->getOffset(), $deletion->getLength());
		$this->shiftChangeOffsetsGreaterThan($deletion->getOffset(), -$deletion->getLength());
	}

	protected function applyInsertion(Insertion $insertion)
	{
		$this->target = substr_replace($this->target, $insertion->getInsertion(), $insertion->getOffset(), 0);
		$insertionLength = strlen($insertion->getInsertion());
		$this->shiftChangeOffsetsGreaterThan($insertion->getOffset() - 1, $insertionLength);
	}

	protected function shiftChangeOffsetsGreaterThan($offset, $shift)
	{
		foreach($this->changes as $change) {
			if($change->getOffset() > $offset) {
				$change->setoffset($change->getoffset() + $shift);
			}
		}
	}
}

