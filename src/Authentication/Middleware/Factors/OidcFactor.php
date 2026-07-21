<?php

namespace NaN\Authentication\Middleware\Factors;

use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Middleware\Factors\Traits\MiddlewareTrait;
use NaN\Http\{
	ResponseFactory,
	ServerRequest,
};
use Psr\Http\Message\{
	ResponseFactoryInterface as PsrResponseFactoryInterface,
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};

class OidcFactor {
	use MiddlewareTrait;

	public function generateChallenge(PsrServerRequestInterface $request): PsrResponseInterface {
		$response_factory = ServerRequest::getServiceFromRequest(
			PsrResponseFactoryInterface::class,
			$request,
			ResponseFactory::class,
		);
	}

	public function validateChallenge(PsrServerRequestInterface $request): ?IdentityInterface {
		return null;
	}
}
