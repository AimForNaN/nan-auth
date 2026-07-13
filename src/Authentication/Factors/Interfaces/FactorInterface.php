<?php

namespace NaN\Authentication\Factors\Interfaces;

use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};

interface FactorInterface {
	public function generateChallenge(PsrServerRequestInterface $request): PsrResponseInterface;

	public function validateChallenge(PsrServerRequestInterface $request): ?IdentityInterface;
}
