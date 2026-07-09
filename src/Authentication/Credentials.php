<?php

namespace NaN\Authentication;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Collections\{
	Collection,
	Interfaces\CollectionInterface,};

class Credentials extends Collection implements CollectionInterface {
	public function __construct(CredentialInterface ...$credentials) {
		parent::__construct(...$credentials);
	}
}
