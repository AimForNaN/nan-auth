<?php

namespace NaN\Authentication\Middleware;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;
use Psr\Http\Message\{
	ResponseInterface,
	ServerRequestInterface,
};
use Psr\Http\Server\{
	MiddlewareInterface,
	RequestHandlerInterface,
};

readonly class Registrator implements MiddlewareInterface {
	public function __construct(
		private RequestValidatorInterface $__request_validator,
	) {
	}

	public function process(
		ServerRequestInterface $request,
		RequestHandlerInterface $handler,
	): ResponseInterface {
		/** @var array $result */
		$result = $this->__request_validator->validateRequest($request);
		/** @var IdentifierInterface|null $identifier */
		$identifier = \array_find($result, fn($x) => $x instanceof IdentifierInterface);
		/** @var CredentialInterface|null $credential */
		$credential = \array_find($result, fn($x) => $x instanceof CredentialInterface);

		return $handler->handle($request);
	}
}
