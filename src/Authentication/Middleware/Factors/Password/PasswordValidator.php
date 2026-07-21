<?php

namespace NaN\Authentication\Middleware\Factors\Password;

use NaN\Authentication\CredentialsCollection;
use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\CredentialType;
use NaN\Authentication\Hashers\Interfaces\HasherInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Http\{
	ResponseFactory,
	ServerRequest,
};
use Psr\Http\{
	Message\ResponseFactoryInterface,
	Message\ResponseInterface as PsrResponseInterface,
	Message\ServerRequestInterface as PsrServerRequestInterface,
	Server\MiddlewareInterface as PsrMiddlewareInterface,
	Server\RequestHandlerInterface as PsrRequestHandlerInterface,
};

readonly class PasswordValidator implements PsrMiddlewareInterface {
	public function __construct(
		private StoreInterface $__identity_store,
		private StoreInterface $__credentials_store,
		private HasherInterface $__hasher,
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
		/** @var CredentialsCollection $credentials */
		$credentials_from_store = $this->__credentials_store->pull([
			'identity' => $identifier->identity,
			'type' => CredentialType::Password->value,
		]);
		/** @var CredentialInterface|null $credential_form_store */
		$credential_form_store = $credentials_from_store->getFirst();

		if ($credential_form_store instanceof CredentialInterface) {
			if (
				$this->__hasher->verify(
					$credential->value,
					$credential_form_store->value,
				)
			) {
				/** @var IdentityInterface|null $identity */
				$identity = $this->__identity_store->pull([
					'id' => $identifier->identity,
				]);

				return $handler->handle(
					$request->withAttribute(
						IdentityInterface::class,
						$identity,
					),
				);
			}
		}

		/** @var ResponseFactoryInterface $response_factory */
		$response_factory = ServerRequest::getServiceFromRequest(
			ResponseFactoryInterface::class,
			$request,
			ResponseFactory::class,
		);

		return $response_factory->createResponse(401);
	}
}
