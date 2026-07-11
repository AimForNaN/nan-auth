<?php

namespace NaN\Authentication\Factors\Traits;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

/**
 * @const CredentialType CREDENTIAL_TYPE
 */
trait FactorTrait {
	public function __construct(
		private RequestValidatorInterface $__request_validator,
		private StoreInterface $__identity_store,
		private StoreInterface $__identifier_store,
		private StoreInterface $__credential_store,
	) {
	}

	public function generateChallenge(): null {
		return null;
	}

	public function validateRequest(PsrServerRequestInterface $request): ?IdentityInterface {
		/** @var array $result */
		$result = $this->__request_validator->validateRequest($request);
		/** @var IdentifierInterface|null $identifier */
		$identifier = \array_find($result, fn($x) => $x instanceof IdentifierInterface);
		/** @var CredentialInterface|null $credential */
		$credential = \array_find($result, fn($x) => $x instanceof CredentialInterface);

		if ($identifier instanceof IdentifierInterface) {
			/**
			 * Identifiers are unique to identities, therefore it is safe
			 *   to pull using only the identifier that we might
			 *   obtain the identity!
			 */
			$identifier = $this->__identifier_store->pull([
				'value' => $identifier->value,
			]);

			if (
				$identifier instanceof IdentifierInterface &&
				$credential instanceof CredentialInterface
			) {
				/** @var CredentialInterface $credential */
				$credential = $credential->withIdentity($identifier->identity);

				if ($this->validateCredential($credential)) {
					return $this->__identity_store->pull([
						'id' => $identifier->identity,
					]);
				}
			}
		}

		return null;
	}
}
