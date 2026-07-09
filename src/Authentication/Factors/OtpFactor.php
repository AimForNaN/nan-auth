<?php

namespace NaN\Authentication\Factors;

use NaN\Authentication\CredentialType;

readonly class OtpFactor implements Interfaces\FactorInterface {
	use Traits\FactorTrait;

	const CredentialType CREDENTIAL_TYPE = CredentialType::OneTimePassword;

	public function generateChallenge(): string {
	}
}
