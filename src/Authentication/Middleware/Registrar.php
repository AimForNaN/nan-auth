<?php

namespace NaN\Authentication\Middleware;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\{
	ResponseFactory,
	ServerRequest,
};
use Psr\Http\Message\{
	ResponseFactoryInterface as PsrResponseFactoryInterface,
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
		/** @var PsrResponseFactoryInterface $response_factory */
		$response_factory = ServerRequest::getServiceFromRequest(
			PsrResponseFactoryInterface::class,
			$request,
			ResponseFactory::class,
		);
		/** @var IdentifierInterface|null $identifier */
		$identifier = ServerRequest::getServiceFromRequest(
			IdentifierInterface::class,
			$request,
		);

		// Require identifier!
		if (empty($identifier)) {
			return $response_factory->createResponse(400, 'Identifier required!');
		}

		// Identity must not already exist!
		if (!empty($identifier->identity)) {
			return $response_factory->createResponse(401);
		}

		/** @var IdentityInterface|null $identity */
		$identity = $this->__identity_store->push();

		// Make sure identity was registered before proceeding!
		if (empty($identity)) {
			return $response_factory->createResponse(500, 'Failed to register identity!');
		}

		/** @var IdentifierInterface|null $identifier */
		$identifier = $identifier->withIdentity($identity->id);

		// Register identifier!
		$this->__identifier_store->push((array)$identifier);

		/** @var CredentialInterface|null $credential */
		$credential = ServerRequest::getServiceFromRequest(
			CredentialInterface::class,
			$request,
		);

		// Do not require credential! Register credential only if one is provided!
		if ($credential instanceof CredentialInterface) {
			$credential = $credential->withIdentity($identity->id);
			$this->__credential_store->push((array)$credential);
		}

		// Pass identity for anyone interested!
		return $handler->handle($request->withAttribute(
			IdentityInterface::class,
			$identity,
		));
	}
}
