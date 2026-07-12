<?php

namespace NaN\Authentication\Credentials;

use NaN\Authentication\Credentials\Traits\CredentialTrait;
use NaN\Authentication\Traits\IdentityRefTrait;
use NaN\Database\Traits\EntityTrait;

class Credential implements Interfaces\CredentialInterface {
	use CredentialTrait, EntityTrait, IdentityRefTrait {
		EntityTrait::fromArray insteadof CredentialTrait;
	}
}
