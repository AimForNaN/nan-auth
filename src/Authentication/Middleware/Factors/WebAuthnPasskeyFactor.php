<?php

namespace NaN\Authentication\Middleware\Factors;

use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Middleware\Factors\Traits\MiddlewareTrait;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};

readonly class WebAuthnPasskeyFactor {
	use MiddlewareTrait;

	public function __construct(
		private RequestValidatorInterface $__request_validator,
		private StoreInterface $__identity_store,
	) {
	}

	public function generateChallenge(PsrServerRequestInterface $request): PsrResponseInterface {
	}

	public function validateChallenge(PsrServerRequestInterface $request): ?IdentityInterface {
		return null;
	}
}
