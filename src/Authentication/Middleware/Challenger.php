<?php

namespace NaN\Authentication\Middleware;

use NaN\Authentication\Factors\Interfaces\FactorInterface;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};
use Psr\Http\Server\{
	MiddlewareInterface as PsrMiddlewareInterface,
	RequestHandlerInterface as PsrRequestHandlerInterface,
};

class Challenger implements PsrMiddlewareInterface {
	public function __construct(
		private FactorInterface $__factor,
	) {
	}

	public function process(
		PsrServerRequestInterface $request,
		PsrRequestHandlerInterface $handler,
	): PsrResponseInterface {
		return $this->__factor->generateChallenge($request);
	}
}
