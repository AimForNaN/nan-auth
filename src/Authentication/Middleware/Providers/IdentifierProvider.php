<?php

namespace NaN\Authentication\Middleware\Providers;

use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Middleware\Traits\MiddlewareTrait;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\{
	ResponseFactory,
	ServerRequest,
};
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;
use Psr\Http\{
	Message\ResponseFactoryInterface as PsrResponseFactoryInterface,
	Message\ResponseInterface as PsrResponseInterface,
	Message\ServerRequestInterface as PsrServerRequestInterface,
	Server\MiddlewareInterface as PsrMiddlewareInterface,
	Server\RequestHandlerInterface as PsrRequestHandlerInterface,
};

/**
 * Pulls identifier from the request and stores it in a request attribute.
 *   Will update the identifier with any matching identifier from the store.
 */
readonly class IdentifierProvider implements PsrMiddlewareInterface {
	use MiddlewareTrait;

	public function __construct(
		private RequestValidatorInterface $__request_validator,
		private StoreInterface $__identifier_store,
	) {
	}

	public function process(
		PsrServerRequestInterface $request,
		PsrRequestHandlerInterface $handler,
	): PsrResponseInterface {
		/** @var array|null $result */
		$result = $this->__request_validator->validateRequest($request);
		/** @var IdentifierInterface|null $identifier */
		$identifier = $this->__getIdentifier($result);

		if (empty($identifier)) {
			/** @var PsrResponseFactoryInterface $response_factory */
			$response_factory = ServerRequest::getServiceFromRequest(
				PsrResponseFactoryInterface::class,
				$request,
				ResponseFactory::class,
			);

			return $response_factory->createResponse(400, 'Identifier required!');
		}

		$identifier_from_store = $this->__identifier_store->pull([
			'value' => $identifier->value,
		]);

		if ($identifier_from_store instanceof IdentifierInterface) {
			$identifier = $identifier_from_store;
		}

		return $handler->handle($request->withAttribute(
			IdentifierInterface::class,
			$identifier,
		));
	}
}
