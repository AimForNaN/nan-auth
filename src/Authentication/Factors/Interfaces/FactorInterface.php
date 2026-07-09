<?php

namespace NaN\Authentication\Factors\Interfaces;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use Psr\Http\Message\ServerRequestInterface;

interface FactorInterface {
	public function generateChallenge(): mixed;

	public function validateRequest(ServerRequestInterface $request): ?IdentityInterface;

	public function validateCredential(CredentialInterface $credential): bool;
}
