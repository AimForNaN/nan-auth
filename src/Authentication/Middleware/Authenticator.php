<?php

namespace NaN\Authentication\Middleware;

use NaN\Authentication\Factors\Interfaces\FactorInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Authentication\Codecs\Interfaces\CodecInterface;
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

readonly class Authenticator implements PsrMiddlewareInterface {
	private \DateTimeInterface $__cookie_expiration;

	public function __construct(
		private FactorInterface $__factor,
		private StoreInterface $__session_store,
		private CodecInterface $__tokenizer,
		private string $__cookie_name = 'session_token',
		?\DateTimeInterface $cookie_expiration  = null,
	) {
		if (!$cookie_expiration) {
			$this->__cookie_expiration = new \DateTimeImmutable('+15 days');
		} else {
			$this->__cookie_expiration = $cookie_expiration;
		}
	}

	/**
	 * @throws \Random\RandomException
	 */
	public function process(
		PsrServerRequestInterface $request,
		PsrRequestHandlerInterface $handler,
	): PsrResponseInterface {
		$identity = $this->__factor->validateRequest($request);

		if ($identity instanceof IdentityInterface) {
			$token = \bin2hex(\random_bytes(64));

			$this->__session_store->set($identity->id, $token);

			$token = $this->__tokenizer->encode($token);

			// @todo Maybe manually set response set-cookie headers.
			setcookie(
				$this->__cookie_name,
				$token,
				[
					'expires' => $this->__cookie_expiration->getTimestamp(),
					'samesite' => 'Lax',
					'secure' => true,
					'httponly' => true,
				],
			);

			return $handler->handle($request);
		}

		/** @var PsrResponseFactoryInterface $response_factory */
		$response_factory = ServerRequest::getServiceFromRequest(
			PsrResponseFactoryInterface::class,
			$request,
		);

		$response_factory ??= new ResponseFactory();

		return $response_factory->createResponse(401);
	}
}
