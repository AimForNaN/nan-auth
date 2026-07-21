<?php

namespace NaN\Authentication\Middleware\Traits;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\CredentialType;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;

trait MiddlewareTrait {
	private function __getCredential(?array $haystack): ?CredentialInterface {
		if (empty($haystack)) {
			return null;
		}

		return \array_find($haystack, function ($credential) {
			return $credential instanceof CredentialInterface;
		});
	}

	private function __getIdentifier(?array $haystack): ?IdentifierInterface {
		if (empty($haystack)) {
			return null;
		}

		return \array_find($haystack, function ($identifier) {
			return $identifier instanceof IdentifierInterface;
		});
	}
}
