<?php

namespace NaN\Authentication\Middleware\Traits;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

trait MiddlewareTrait {
	private function __getCredential(
		PsrServerRequestInterface $request,
	): ?CredentialInterface {
		$haystack = $this->__getData($request);

		return \array_find($haystack, function ($credential) {
			return $credential instanceof CredentialInterface;
		});
	}

	private function __getData(
		PsrServerRequestInterface $request,
	): array {
		return match ($request->getMethod()) {
			'GET' => $request->getQueryParams(),
			'POST', 'PUT' => $request->getParsedBody(),
			default => [],
		};
	}

	private function __getIdentifier(
		PsrServerRequestInterface $request,
	): ?IdentifierInterface {
		$haystack = $this->__getData($request);

		return \array_find($haystack, function ($identifier) {
			return $identifier instanceof IdentifierInterface;
		});
	}
}
