<?php

namespace PhpFileops;

use Relike\MatchResult;


class TokenMatch extends MatchResult
{
	protected $sourceOffset;
	protected $sourceLength;

	public function __construct($offset, $length, $sourceOffset, PhpTokenList $tokenList, array $namedGroups = array())
	{
		parent::__construct($offset, $length, $tokenList, $namedGroups);
		$this->sourceOffset = $sourceOffset;
		$this->sourceLength = $this->calculateSourceLength($tokenList);
	}

	protected static function calculateSourceLength(PhpTokenList $tokenList, $offset = 0, $length = null)
	{
		$tokenList = new PhpTokenList(array_slice($tokenList->getTokens(), $offset, $length));
		return strlen($tokenList->getSource());
	}

	public static function newFromResultMatch(MatchResult $resultMatch, PhpTokenList $hayStack)
	{
		$namedGroups = $resultMatch->getNamedGroups();
		foreach($namedGroups as &$namedGroup) {
			$namedGroup = self::newFromResultMatch($namedGroup, $hayStack);
		}
		return new self(
			$resultMatch->getOffset(),
			$resultMatch->getLength(),
			self::calculateSourceLength($hayStack, 0, $resultMatch->getOffset()),
			new PhpTokenList($resultMatch->getMatch()),
			$namedGroups
		);
	}

	public static function arrayToSource(array $tokenMatches)
	{
		$sources = array();
		foreach($tokenMatches as $match) {
			$sources[] = $match->getMatch()->getSource();
		}
		return $sources;
	}

	public static function newFromResultMatches(array $resultMatches, PhpTokenList $haystack)
	{
		$tokenMatches = array();
		foreach ($resultMatches as $resultMatch) {
			$tokenMatches[] = self::newFromResultMatch($resultMatch, $haystack);
		}
		return $tokenMatches;
	}

 	public function getTokenList()
 	{
 		return $this->match;
 	}
 
 	public function getSourceOffset()
 	{
 		return $this->sourceOffset;
 	}
 
 	public function getSourceLength()
 	{
 		return $this->sourceLength;
 	}
}

