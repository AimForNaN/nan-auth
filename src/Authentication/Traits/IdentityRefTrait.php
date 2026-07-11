<?php

namespace NaN\Authentication\Traits;

use NaN\Authentication\Interfaces\IdentityRefInterface;

/**
 * @implements IdentityRefInterface
 */
trait IdentityRefTrait {
	private(set) ?string $identity = null;

	public function withIdentity(string $identity): IdentityRefInterface {
		$clone = clone $this;

		$clone->identity = $identity;

		return $clone;
	}
}
