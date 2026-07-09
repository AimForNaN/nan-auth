<?php

namespace NaN\Authentication\Stores\Interfaces;

interface StoreInterface {
	public function get(mixed $key): mixed;

	public function set(mixed $key, mixed $value): StoreInterface;
}
