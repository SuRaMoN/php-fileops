<?php

namespace PhpFileops;

use Relike\MatchResult;
use Relike\TokenArrayRelike;


class PhpSourceSearcher
{
	protected $relike;

	public function __construct()
	{
		$this->relike = new TokenArrayRelike();
	}

	public function findNamespaceDefinition(PhpTokenList $tokenList)
	{
		$s = $this->relike;
		$namespaceDefinition = $s->sequence(
			T_NAMESPACE, $s->multiple(T_WHITESPACE),
			$s->named('name', $s->optional(T_NS_SEPARATOR), $s->multiple(T_STRING, T_NS_SEPARATOR), T_STRING)
		);
		$match = $s->find($tokenList->getTokens(), $namespaceDefinition);
		return TokenMatch::newFromResultMatch($match, $tokenList);
	}

	public function findNamespaceName(PhpTokenList $tokenList)
	{
		$namepaceDefinition = $this->findNamespaceDefinition($tokenList);
		return $namepaceDefinition->getNamedGroup('name');
	}

	public function findClassUsages(PhpTokenList $tokenList)
	{
		$s = $this->relike;
		$newClassUsage = $s->sequence(
			T_NEW, $s->multiple(T_WHITESPACE),
			$s->named('name', $s->optional(T_NS_SEPARATOR), $s->multiple(T_STRING, T_NS_SEPARATOR), T_STRING)
		);
		$matches = $s->findAll($tokenList->getTokens(), $newClassUsage);

		$staticClassUsage = $s->sequence(
			$s->named('name', $s->optional(T_NS_SEPARATOR), $s->multiple(T_STRING, T_NS_SEPARATOR), T_STRING),
			$s->multiple(T_WHITESPACE), T_PAAMAYIM_NEKUDOTAYIM
		);

		$matches = array_merge($matches, $s->findAll($tokenList->getTokens(), $staticClassUsage));

		foreach($matches as &$match) {
			$match = $match->getNamedGroup('name');
		}

		return TokenMatch::newFromResultMatches($matches, $tokenList);
	}
}

