<?php

namespace NaN\Authentication\Factors;

use NaN\Authentication\Credentials\{
	Credential,
	Interfaces\CredentialInterface,
};
use NaN\Authentication\CredentialType;
use NaN\Authentication\Hashers\Interfaces\HasherInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;
use NaN\Http\{
	ResponseFactory,
	ServerRequest,
};
use Psr\Http\Message\{
	ResponseFactoryInterface as PsrResponseFactoryInterface,
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};

readonly class PasswordFactor implements Interfaces\FactorInterface {
	const CredentialType CREDENTIAL_TYPE = CredentialType::Password;

	public function __construct(
		private RequestValidatorInterface $__request_validator,
		private StoreInterface $__identity_store,
		private StoreInterface $__identifier_store,
		private StoreInterface $__credential_store,
		private HasherInterface $__hasher,
	) {
	}

	public function generateChallenge(PsrServerRequestInterface $request): PsrResponseInterface {
		$response_factory = ServerRequest::getServiceFromRequest(
			PsrResponseFactoryInterface::class,
			$request,
			ResponseFactory::class,
		);

		return $response_factory->createResponse(204);
	}

	public function validateChallenge(PsrServerRequestInterface $request): ?IdentityInterface {
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
				$credential_with_hash = $credential->withValue($this->__hasher->hash($credential->value));
				$credentials_from_store = $this->__credential_store->pull($credential_with_hash->jsonSerialize());

				if (\count($credentials_from_store)) {
					foreach ($credentials_from_store as $credential_from_store) {
						if ($this->__hasher->verify($credential->value, $credential_from_store->value)) {
							return $this->__identity_store->pull([
								'id' => $identifier->identity,
							]);
						}
					}
				}
			}
		}

		return null;
	}
}
