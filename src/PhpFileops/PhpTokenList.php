<?php

namespace PhpFileops;


class PhpTokenList
{
	const TOKEN_ARRAY = -1;
	const TOKEN_INDEX = 0;
	const CONTENT = 1;
	const LINE_NUMBER = 2;

	protected $tokens;

	public function __construct(array $tokens)
	{
		foreach($tokens as &$token) {
			switch($token) {
				case '(': case ')': case ';': case '{': case '}': case '=': case ',':
					$token = array(-ord($token), $token, -1); break;
			}
		}
		$this->tokens = $tokens;
	}

	public static function newInstance(array $tokens)
	{
		return new self($tokens);
	}

	public static function newFromSource($source)
	{
		return new self(token_get_all($source));
	}

	public static function newFromFilePath($filePath)
	{
		return self::newFromSource(file_get_contents($filePath));
	}

	public function nth($i, $type = self::TOKEN_ARRAY)
	{
		if($type == self::TOKEN_ARRAY) {
			return $this->tokens[$i];
		} else {
			return $this->tokens[$i][$type];
		}
	}

	public function getCount()
	{
		return count($this->tokens);
	}
 
	public function getTokens()
	{
		return $this->tokens;
	}

	public function getSourceArray()
	{
		return array_map(function (array $idArray) {
			return $idArray[PhpTokenList::CONTENT];
		}, $this->tokens);
	}

	public function getTokenIndexArray()
	{
		return array_map(function (array $idArray) {
			return $idArray[PhpTokenList::TOKEN_INDEX];
		}, $this->tokens);
	}

	public function ltrim(array $tokenIds)
	{
		$tokens = $this->tokens;
		foreach($tokenIds as $tokenId) {
			while(count($tokens) > 0 && $tokens[0][self::TOKEN_INDEX] == $tokenId) {
				array_shift($tokens);
			}
		}
		return new self($tokens);
	}

	public function rtrim(array $tokenIds)
	{
		$tokens = $this->tokens;
		foreach($tokenIds as $tokenId) {
			while(count($tokens) > 0 && $tokens[count($tokens) - 1][self::TOKEN_INDEX] == $tokenId) {
				array_pop($tokens);
			}
		}
		return new self($tokens);
	}

	public function trim(array $tokenIds)
	{
		return $this->ltrim($tokenIds)->rtrim($tokenIds);
	}

	public function getSource()
	{
		return implode('', $this->getSourceArray());
	}
}

