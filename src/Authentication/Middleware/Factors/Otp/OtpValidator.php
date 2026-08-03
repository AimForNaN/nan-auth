<?php

namespace NaN\Authentication\Middleware\Factors\Otp;

use NaN\Authentication\Credentials\Interfaces\CredentialInterface;
use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Http\{
	ResponseFactory,
	ServerRequest,
};
use OTPHP\TOTP;
use Psr\Http\{
	Message\ResponseFactoryInterface as PsrResponseFactoryInterface,
	Message\ResponseInterface as PsrResponseInterface,
	Message\ServerRequestInterface as PsrServerRequestInterface,
	Server\MiddlewareInterface as PsrMiddlewareInterface,
	Server\RequestHandlerInterface as PsrRequestHandlerInterface,
};
use Psr\SimpleCache\CacheInterface;

readonly class OtpValidator implements PsrMiddlewareInterface {
	public function __construct(
		private CacheInterface $__secret_store,
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
		$identifier = $request->getAttribute(IdentifierInterface::class);
		/** @var CredentialInterface|null $otp */
		$otp = $request->getAttribute(CredentialInterface::class);

		if (empty($identifier)) {
			return $response_factory->createResponse(401);
		}

		if (!$this->__secret_store->has($identifier->value)) {
			return $response_factory->createResponse(401);
		}

		/** @var string $otp_secret */
		$otp_secret = $this->__secret_store->get($identifier->value);

		$totp_clock = \Carbon\FactoryImmutable::getCurrentClock();
		$totp = TOTP::createFromSecret($otp_secret, $totp_clock);

		$totp->setLabel($identifier->value);
		$totp->setPeriod(OtpChallenger::OTP_PERIOD);
		$totp->setDigest(OtpChallenger::OTP_DIGEST);
		$totp->setDigits(OtpChallenger::OTP_DIGITS);

		if (
			$otp instanceof CredentialInterface &&
			$totp->verify($otp->value)
		) {
			return $handler->handle($request);
		}

		return $response_factory->createResponse(401);
	}
}
