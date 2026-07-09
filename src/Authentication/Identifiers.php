<?php

namespace NaN\Authentication;

use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Collections\{
	Collection,
	Interfaces\CollectionInterface,};

class Identifiers extends Collection implements CollectionInterface {
	public function __construct(IdentifierInterface ...$identifiers) {
		parent::__construct(...$identifiers);
	}
}
