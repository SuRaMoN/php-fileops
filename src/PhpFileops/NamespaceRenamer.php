<?php

namespace PhpFileops;

use PhpFileops\StringChangeSet\StringChangeSet;
use Relike\Exceptions\NoMatchFoundException;
use Relike\Relike;


class NamespaceRenamer
{
	protected $tokenList;
	protected $sourceChangeSet;
	protected $namespace;
	protected $from;
	protected $to;
	protected $phpSourceSearcher;

	public function __construct($sourceFilePath, $from, $to)
	{
		$this->tokenList = PhpTokenList::newFromFilePath($sourceFilePath);
		$this->from = '\\' . trim($from, '\\') . '\\';
		$this->to = '\\' . trim($to, '\\') . '\\';
		$this->phpSourceSearcher = new PhpSourceSearcher();
	}

	public function rename()
	{
		$this->sourceChangeSet = new StringChangeSet($this->tokenList->getSource());
		$this->renameNamespace();
		foreach($this->phpSourceSearcher->findClassUsages($this->tokenList) as $classMatch) {
			$this->renameClassUsage($classMatch);
		}
		return $this->sourceChangeSet->apply()->getString();
	}

	protected function renameClassUsage(TokenMatch $classMatch)
	{
		$oldFullClassName = $this->getFullClassName($classMatch->getMatch()->getSource());
		if(strpos($oldFullClassName, $this->from) !== 0) {
			return;
		}
		$newClassName = $this->to . substr($oldFullClassName, strlen($this->from));
		if(strpos($newClassName, $this->namespace) === 0) {
			$newClassName = substr($newClassName, strlen($this->namespace));
		}
		$this->sourceChangeSet->addReplace($classMatch->getMatch()->getSource(), $newClassName, $classMatch->getSourceOffset());
	}

	protected function getFullClassName($className)
	{
		if(substr($className, 0, 1) == '\\') {
			return $className;
		}
		return $this->namespace . $className;
	}

	protected function renameNamespace()
	{
		try {
			$namespaceMatch = $this->phpSourceSearcher->findNamespaceName($this->tokenList);
			$oldNamespace = '\\' . trim($namespaceMatch->getMatch()->getSource(), '\\') . '\\';
			if($oldNamespace != $this->from) {
				throw new NoMatchFoundException('Could not find namespace');
			}
			$this->namespace = $this->to;
			$this->sourceChangeSet->addReplace($namespaceMatch->getMatch()->getSource(), trim($this->to, '\\'), $namespaceMatch->getSourceOffset());
		} catch(NoMatchFoundException $e) {
			$this->namespace = '\\';
		}
	}

	public static function doRename($source, $from, $to)
	{
		$renamer = new self($source, $from, $to);
		return $renamer->rename();
	}
}
 
