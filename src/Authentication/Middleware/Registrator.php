<?php

namespace NaN\Authentication\Middleware;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};
use Psr\Http\Server\{
	MiddlewareInterface as PsrMiddlewareInterface,
	RequestHandlerInterface as PsrRequestHandlerInterface,
};

readonly class Registrator implements PsrMiddlewareInterface {
	public function __construct(
		private RequestValidatorInterface $__request_validator,
		private StoreInterface $__identity_store,
		private StoreInterface $__identifier_store,
	) {
	}

	public function process(
		PsrServerRequestInterface $request,
		PsrRequestHandlerInterface $handler,
	): PsrResponseInterface {
		/** @var array $result */
		$result = $this->__request_validator->validateRequest($request);
		/** @var IdentifierInterface|null $identifier */
		$identifier = \array_find($result, fn($x) => $x instanceof IdentifierInterface);
		/** @var CredentialInterface|null $credential */
		$credential = \array_find($result, fn($x) => $x instanceof CredentialInterface);

		return $handler->handle($request);
	}
}
