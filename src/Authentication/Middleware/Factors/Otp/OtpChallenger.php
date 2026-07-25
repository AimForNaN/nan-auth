<?php

namespace NaN\Authentication\Middleware\Factors\Otp;

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
	Server\RequestHandlerInterface as PsrRequestHandlerInterface};
use Psr\SimpleCache\CacheInterface;

readonly class OtpChallenger implements PsrMiddlewareInterface{
	const string OTP_DIGEST = 'sha256';
	const int OTP_DIGITS = 8;
	const int OTP_PERIOD = 60 * 15;

	public function __construct(
		private CacheInterface $__secret_store,
		private \Closure $__sender,
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

		if (empty($identifier)) {
			return $response_factory->createResponse(400, 'Identifier required!');
		}

		$totp_clock = \Carbon\FactoryImmutable::getCurrentClock();
		$totp = TOTP::generate($totp_clock);

		$totp->setLabel($identifier->value);
		$totp->setDigest(self::OTP_DIGEST);
		$totp->setDigits(self::OTP_DIGITS);
		$totp->setPeriod(self::OTP_PERIOD);

		if (!$this->__secret_store->set($identifier->value, $totp->getSecret(), self::OTP_PERIOD)) {
			return $response_factory->createResponse(500);
		}

		$this->__sender($totp->now());

		return $handler->handle($request);
	}
}
