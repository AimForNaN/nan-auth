<?php

namespace NaN\Authentication\Schemas\Traits;

use Nette\Schema\{Context, Schema};
use NaN\Authentication\Interfaces\SessionInterface;

/**
 * @property string $__type
 */
trait SchemaTrait {
	private Schema $__schema;

	public function complete(mixed $value, Context $context) {
		return [
			'type' => $this->__type,
			'value' => $value,
		];
	}

	public function completeDefault(Context $context) {
		return $this->__schema->completeDefault($context);
	}

	public function merge(mixed $value, mixed $base) {
		return $this->__schema->merge($value, $base);
	}

	public function normalize(mixed $value, Context $context) {
		return $this->__schema->normalize($value, $context);
	}

	protected function _assertClass(string $class, string $interface): void {
		if (!\is_subclass_of($class, $interface)) {
			throw new \ValueError(
				\sprintf(
					'%s does not implement the %s interface!',
					$class,
					$interface,
				),
			);
		}
	}
}
