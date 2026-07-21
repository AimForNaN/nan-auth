<?php

namespace NaN\Authentication\Middleware\Providers;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Middleware\Traits\MiddlewareTrait;
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;
use NaN\Http\{
	ResponseFactory,
	ServerRequest,
};
use Psr\Http\{
	Message\ResponseFactoryInterface as PsrResponseFactoryInterface,
	Message\ResponseInterface as PsrResponseInterface,
	Message\ServerRequestInterface as PsrServerRequestInterface,
	Server\MiddlewareInterface as PsrMiddlewareInterface,
	Server\RequestHandlerInterface as PsrRequestHandlerInterface,
};

/**
 * Pulls credential from the request and stores it in a request attribute.
 */
readonly class CredentialProvider implements PsrMiddlewareInterface {
	use MiddlewareTrait;

	public function __construct(
		private RequestValidatorInterface $__request_validator,
	) {
	}

	public function process(
		PsrServerRequestInterface $request,
		PsrRequestHandlerInterface $handler,
	): PsrResponseInterface {
		/** @var array|null $result */
		$result = $this->__request_validator->validateRequest($request);
		/** @var CredentialInterface|null $credential */
		$credential = $this->__getCredential($result);

		if (empty($credential)) {
			/** @var PsrResponseFactoryInterface $response_factory */
			$response_factory = ServerRequest::getServiceFromRequest(
				PsrResponseFactoryInterface::class,
				$request,
				ResponseFactory::class,
			);

			return $response_factory->createResponse(400, 'Credential required!');
		}

		return $handler->handle(
			$request->withAttribute(
				CredentialInterface::class,
				$credential,
			),
		);
	}
}
