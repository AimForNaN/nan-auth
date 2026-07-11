<?php

namespace NaN\Authentication\Credentials;

use NaN\Authentication\Traits\EntityTrait;
use NaN\Authentication\Traits\IdentityRefTrait;

class Credential implements Interfaces\CredentialInterface {
	use EntityTrait;
	use IdentityRefTrait;
	use Traits\CredentialTrait;
}
