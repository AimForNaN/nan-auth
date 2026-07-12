<?php

namespace NaN\Authentication\Identifiers\Traits;

use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\IdentifierType;

/**
 * @implements IdentifierInterface
 */
trait IdentifierTrait {
	public string $id {
		get {
			return $this->value;
		}
	}

	private(set) IdentifierType $type;

	private(set) mixed $value;

	static function fromArray(iterable $arr): IdentifierInterface {
		$new = new self();

		foreach ($arr as $key => $value) {
			$new->$key = $value;
		}

		return $new;
	}

	public function withId(string $id): IdentifierInterface {
		return $this->withValue($id);
	}

	public function withType(IdentifierType $type): IdentifierInterface {
		$clone = clone $this;

		$clone->type = $type;

		return $clone;
	}

	public function withValue(string $value): IdentifierInterface {
		$clone = clone $this;

		$clone->value = $value;

		return $clone;
	}
}
