<?php

namespace NaN\Authentication\Factors;

use NaN\Authentication\Credentials\{
	Credential,
	Interfaces\CredentialInterface,
};
use NaN\Authentication\CredentialType;
use NaN\Authentication\Hashers\Interfaces\HasherInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;

readonly class PasswordFactor implements Interfaces\FactorInterface {
	use Traits\FactorTrait;

	const CredentialType CREDENTIAL_TYPE = CredentialType::Password;

	public function __construct(
		private RequestValidatorInterface $__request_validator,
		private StoreInterface $__identity_store,
		private StoreInterface $__identifier_store,
		private StoreInterface $__credential_store,
		private HasherInterface $__hasher,
	) {
	}

	public function validateCredential(CredentialInterface $credential): bool {
		$credential_with_hash = $credential->withValue($this->__hasher->hash($credential->value));
		$credential_from_store = $this->__credential_store->pull((array)$credential_with_hash);

		if ($credential_from_store instanceof Credential) {
			return $this->__hasher->verify($credential->value, $credential_from_store->value);
		}

		return false;
	}
}
