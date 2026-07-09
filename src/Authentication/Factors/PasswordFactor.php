<?php

namespace NaN\Authentication\Factors;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
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
		$credential = $credential->withValue($this->__hasher->hash($credential->value));
		$credentials_from_store = $this->__credential_store->get($credential);

		if (!empty($credentials_from_store)) {
			// Assumes only one matching result!
			[$credential_from_store] = $credentials_from_store;
			return $this->__hasher->verify($credential->value, $credential_from_store->value);
		}

		return false;
	}
}
