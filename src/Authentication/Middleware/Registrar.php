<?php

namespace NaN\Authentication\Middleware;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\ServerRequest;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};
use Psr\Http\Server\{
	MiddlewareInterface as PsrMiddlewareInterface,
	RequestHandlerInterface as PsrRequestHandlerInterface,
};

/**
 * The registrator has to first check if the identifier is already registered.
 *   If not, then register the identifier and credential and return success.
 *
 * But what if you want the user to pass a challenge first?
 *   Perhaps, chain middleware together where the challenge is first validated.
 */
readonly class Registrar implements PsrMiddlewareInterface {
	public function __construct(
		private StoreInterface $__identity_store,
		private StoreInterface $__identifier_store,
		private StoreInterface $__credential_store,
	) {
	}

	public function process(
		PsrServerRequestInterface $request,
		PsrRequestHandlerInterface $handler,
	): PsrResponseInterface {
		/** @var IdentifierInterface|null $identifier */
		$identifier = ServerRequest::getServiceFromRequest(
			IdentifierInterface::class,
			$request,
		);
		/** @var CredentialInterface|null $credential */
		$credential = ServerRequest::getServiceFromRequest(
			CredentialInterface::class,
			$request,
		);
		/** @var IdentityInterface|null $identity */
		$identity = $identifier->identity;

		if (empty($identity)) {
			$identity = $this->__identity_store->push();
			$identifier = $identifier->withIdentity($identity->id);
			$credential = $credential->withIdentity($identity->id);

			$this->__identifier_store->push((array)$identifier);
			$this->__credential_store->push((array)$credential);
		}

		return $handler->handle($request);
	}
}
