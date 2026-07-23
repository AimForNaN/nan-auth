<?php

namespace NaN\Authentication\Middleware\Providers;

use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\ServerRequest;
use Psr\Http\{
	Message\ResponseInterface as PsrResponseInterface,
	Message\ServerRequestInterface as PsrServerRequestInterface,
	Server\MiddlewareInterface as PsrMiddlewareInterface,
	Server\RequestHandlerInterface as PsrRequestHandlerInterface,
};

readonly class IdentityFromIdentifierProvider implements PsrMiddlewareInterface {
	public function __construct(
		private StoreInterface $__identity_store,
		private StoreInterface $__identifier_store,
	) {
	}

	public function process(
		PsrServerRequestInterface $request,
		PsrRequestHandlerInterface $handler,
	): PsrResponseInterface {
		/** @var IdentityInterface|null $identity */
		$identity = null;
		/** @var IdentifierInterface|null $identifier */
		$identifier = ServerRequest::getServiceFromRequest(
			IdentifierInterface::class,
			$request,
		);

		if (\is_a($identifier, IdentifierInterface::class)) {
			if (!empty($identifier->identity)) {
				$identity = $this->__identity_store->pull([
					'id' => $identifier->identity,
				]);
			}
		}

		return $handler->handle(
			$request->withAttribute(
				IdentityInterface::class,
				$identity,
			),
		);
	}
}
