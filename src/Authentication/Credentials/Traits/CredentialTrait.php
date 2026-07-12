<?php

namespace NaN\Authentication\Credentials\Traits;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\CredentialType;

/**
 * @implements CredentialInterface
 */
trait CredentialTrait {
	private(set) CredentialType $type;

	private(set) string $value;

	static function fromArray(iterable $arr): CredentialInterface {
		$new = new self();

		foreach ($arr as $key => $value) {
			$new->$key = $value;
		}

		return $new;
	}

	public function withType(CredentialType $type): CredentialInterface {
		$clone = clone $this;

		$clone->type = $type;

		return $clone;
	}

	public function withValue(string $value): CredentialInterface {
		$clone = clone $this;

		$clone->value = $value;

		return $clone;
	}
}
