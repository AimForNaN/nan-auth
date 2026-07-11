<?php

namespace NaN\Authentication\Sessions\Traits;

use NaN\Authentication\Sessions\Interfaces\SessionInterface;

/**
 * @implements SessionInterface
 */
trait SessionTrait {
	private(set) string $token;

	public static function fromArray(array $data): SessionInterface {
		$new = new self();

		foreach ($data as $key => $value) {
			$new->{$key} = $value;
		}

		return $new;
	}

	public function withToken(string $token): SessionInterface {
		$clone = clone $this;

		$clone->token = $token;

		return $clone;
	}
}
