<?php

namespace NaN\Authentication\Identities\Traits;

use NaN\Authentication\Identities\Interfaces\IdentityInterface;

/**
 * @implements IdentityInterface
 */
trait IdentityTrait {
	static function fromArray(array $arr): IdentityInterface {
		$new = new self();

		foreach ($arr as $key => $value) {
			$new->$key = $value;
		}

		return $new;
	}
}
