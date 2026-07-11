<?php

namespace NaN\Authentication\Traits;

use NaN\Authentication\Interfaces\EntityInterface;

/**
 * @implements EntityInterface
 */
trait EntityTrait {
	public string $id;

	public function withId(string $id): EntityInterface {
		$clone = clone $this;

		$clone->id = $id;

		return $clone;
	}
}
