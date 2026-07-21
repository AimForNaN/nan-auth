<?php

namespace NaN\Authentication\Middleware\Factors;

use NaN\Authentication\Hashers\Interfaces\HasherInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Middleware\Factors\Traits\MiddlewareTrait;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};

/**
 * OTP factor stores OTP codes with the identifier as its key.
 *   It is intended to be used only with a Challenger and Validator!
 */
readonly class OtpFactor {
	use MiddlewareTrait;

	public function __construct(
		private StoreInterface $__challenge_store,
		private HasherInterface $__hasher,
	) {
	}

	public function generateChallenge(PsrServerRequestInterface $request): PsrResponseInterface {
	}

	public function validateChallenge(PsrServerRequestInterface $request): ?IdentityInterface {
	}
}
