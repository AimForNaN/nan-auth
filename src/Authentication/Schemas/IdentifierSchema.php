<?php

namespace NaN\Authentication\Schemas;

use NaN\Authentication\Identifiers\Identifier;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\IdentifierType;
use Nette\Schema\{
	Context,
	Expect,
	Schema,
};

readonly class IdentifierSchema implements Schema {
	use Traits\SchemaTrait;

	public function __construct(
		private string $__class,
		private IdentifierType $__type,
	) {
		$this->_assertClass($this->__class, IdentifierInterface::class);

		if ($this->__type == IdentifierType::Email) {
			$this->__schema = Expect::email();
		} else {
			$this->__schema = Expect::string();
		}

		$this->__schema->required();
	}

	public function complete(mixed $value, Context $context) {
		return new $this->__class()
			->withType($this->__type)
			->withValue($value)
		;
	}
}
