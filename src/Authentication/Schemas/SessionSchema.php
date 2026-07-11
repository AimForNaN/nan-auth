<?php

namespace NaN\Authentication\Schemas;

use NaN\Authentication\Sessions\Interfaces\SessionInterface;
use Nette\Schema\{
	Context,
	Expect,
	Schema,
};

readonly class SessionSchema implements Schema {
	use Traits\SchemaTrait;

	public function __construct(
		private string $__class,
	) {
		$this->_assertClass($this->__class, SessionInterface::class);

		$this->__schema = Expect::string()->required();
	}

	public function complete(mixed $value, Context $context) {
		return $this->__class::fromArray([
			'token' => $value,
		]);
	}
}
