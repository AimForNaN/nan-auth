<?php

namespace NaN\Authentication\Middleware\Traits;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;

trait MiddlewareTrait {
	private function __getCredential(?array $haystack): ?CredentialInterface {
		return \array_find($haystack ?? [], function ($credential) {
			return $credential instanceof CredentialInterface;
		});
	}

	private function __getIdentifier(?array $haystack): ?IdentifierInterface {
		return \array_find($haystack ?? [], function ($identifier) {
			return $identifier instanceof IdentifierInterface;
		});
	}
}
