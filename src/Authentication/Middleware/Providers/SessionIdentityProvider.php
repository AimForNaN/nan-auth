<?php

namespace NaN\Authentication\Middleware\Providers;

use NaN\Authentication\Codecs\Interfaces\CodecInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Sessions\{
	Interfaces\SessionInterface,
	Traits\AssertSessionTrait,
};
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\RequestValidators\Interfaces\RequestValidatorInterface;
use Psr\Http\{
	Message\ResponseInterface as PsrResponseInterface,
	Message\ServerRequestInterface as PsrServerRequestInterface,
	Server\MiddlewareInterface as PsrMiddlewareInterface,
	Server\RequestHandlerInterface as PsrRequestHandlerInterface,
};

readonly class SessionIdentityProvider implements PsrMiddlewareInterface {
	use AssertSessionTrait;

	public function __construct(
		private RequestValidatorInterface $__request_validator,
		private StoreInterface $__identity_store,
		private StoreInterface $__session_store,
		private CodecInterface $__codec,
	) {
	}

	public function process(
		PsrServerRequestInterface $request,
		PsrRequestHandlerInterface $handler,
	): PsrResponseInterface {
		/** @var array $data */
		if ($data = $this->__request_validator->validateRequest($request)) {
			/** @var SessionInterface|null $session_cookie */
			$session_cookie = \array_find($data, fn($x) => $x instanceof SessionInterface);

			$this->__assertSession($session_cookie);

			$decoded_token = $this->__codec->decode($session_cookie->token);
			/** @var SessionInterface|null $session */
			$session = $this->__session_store->pull([
				'token' => $decoded_token,
			]);

			$this->__assertSession($session);

			/** @var IdentityInterface|null $user */
			$user = $this->__identity_store->pull([
				'id' => $session->identity,
			]);

			if ($user instanceof IdentityInterface) {
				$request = $request->withAttribute(IdentityInterface::class, $user);
			}
		}

		return $handler->handle($request);
	}
}
