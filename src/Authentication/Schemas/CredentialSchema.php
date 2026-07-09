<?php

namespace NaN\Authentication\Schemas;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\CredentialType;
use Nette\Schema\{
	Context,
	Expect,
	Schema,
};

readonly class CredentialSchema implements Schema {
	use Traits\SchemaTrait;

	public function __construct(
		private string $__class,
		private CredentialType $__type,
	) {
		$this->_assertClass($this->__class, CredentialInterface::class);

		$this->__schema = Expect::string()->required();
	}

	function complete(mixed $value, Context $context) {
		return new $this->__class()
			->withType($this->__type)
			->withValue($value)
		;
	}
}
