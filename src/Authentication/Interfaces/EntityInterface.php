<?php

namespace NaN\Authentication\Interfaces;

interface EntityInterface {
	public ?string $id { get; }

	public function withId(string $id): EntityInterface;
}
