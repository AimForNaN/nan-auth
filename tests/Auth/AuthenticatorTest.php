<?php

use Illuminate\Database\Capsule\Manager as CapsuleManager;
use NaN\Authentication\Caches\MapCache;
use NaN\Authentication\Caches\RedisCache;
use NaN\Authentication\Codecs\SymmetricPasetoCodec;
use NaN\Authentication\Credentials\Credential;
use NaN\Authentication\CredentialType;
use NaN\Authentication\Hashers\BcryptHasher;
use NaN\Authentication\Identifiers\Identifier;
use NaN\Authentication\IdentifierType;
use NaN\Authentication\Middleware\Authenticator;
use NaN\Authentication\Middleware\Factors\Otp\OtpChallenger;
use NaN\Authentication\Middleware\Factors\Otp\OtpValidator;
use NaN\Authentication\Middleware\Factors\Password\PasswordValidator;
use NaN\Authentication\Middleware\Providers\CredentialProvider;
use NaN\Authentication\Middleware\Providers\IdentifierProvider;
use NaN\Authentication\Middleware\Providers\IdentityFromIdentifierProvider;
use NaN\Authentication\Schemas\CredentialSchema;
use NaN\Authentication\Schemas\IdentifierSchema;
use NaN\Authentication\Stores\Sql\SqlCredentialStore;
use NaN\Authentication\Stores\Sql\SqlIdentifierStore;
use NaN\Authentication\Stores\Sql\SqlIdentityStore;
use NaN\Authentication\Stores\Sql\SqlSessionStore;
use NaN\Collections\Middleware\MiddlewareCollection;
use NaN\Database\Sql\SqlConnection;
use NaN\Http\RequestHandlers\ClosureRequestHandler;
use NaN\Http\RequestValidators\PostRequestValidator;
use NaN\Http\ResponseFactory;
use NaN\Http\ServerRequestFactory;
use Nette\Schema\Expect;
use Nette\Schema\Processor;

describe('Authenticator', function () {
	test('OTPs', function () {
		$pdo = CapsuleManager::connection()->getPdo();
		$connection = new SqlConnection($pdo);
		$shared_key = \random_bytes(32);
		$cache = new MapCache();
		$otp = null;
		$schema = Expect::array([
			'identifier' => new IdentifierSchema(Identifier::class, IdentifierType::Email),
		]);
		$middleware = new MiddlewareCollection(
			new PostRequestValidator($schema),
			new IdentifierProvider(
				new SqlIdentifierStore($connection),
			),
			new OtpChallenger($cache, function ($totp) use (&$otp) {
				$otp = $totp;
			}),
		);

		$request = new ServerRequestFactory()
			->createServerRequest('POST', '/')
			->withParsedBody([
				'identifier' => 'test@example.com',
			])
		;
		$rsp = $middleware->process(
			$request,
			new ClosureRequestHandler(function () {
				return new ResponseFactory()->createResponse();
			}),
		);

		expect($rsp->getStatusCode())
			->toBe(200)
			->and($rsp->getReasonPhrase())
				->toBe('OK')
		;

		$schema = Expect::array([
			'identifier' => new IdentifierSchema(Identifier::class, IdentifierType::Email),
			'credential' => new CredentialSchema(Credential::class, CredentialType::Otp),
		]);
		$middleware = new MiddlewareCollection(
			new PostRequestValidator($schema),
			new IdentifierProvider(
				new SqlIdentifierStore($connection),
			),
			new CredentialProvider(),
			new OtpValidator($cache),
			new IdentityFromIdentifierProvider(
				new SqlIdentityStore($connection),
			),
			new Authenticator(
				new SqlSessionStore($connection),
				new SymmetricPasetoCodec($shared_key)
			),
		);
		$request = new ServerRequestFactory()
			->createServerRequest('POST', '/')
			->withParsedBody([
				'identifier' => 'test@example.com',
				'credential' => $otp,
			])
		;
		$rsp = $middleware->process(
			$request,
			new ClosureRequestHandler(function () {
				return new ResponseFactory()->createResponse();
			}),
		);

		expect($rsp->getStatusCode())
			->toBe(200)
			->and($rsp->getReasonPhrase())
				->toBe('OK')
		;
	});

	test('Passwords', function () {
		$pdo = CapsuleManager::connection()->getPdo();
		$connection = new SqlConnection($pdo);
		$shared_key = \random_bytes(32);
		$middleware = new MiddlewareCollection(
			new PostRequestValidator(Expect::array([
				'identifier' => new IdentifierSchema(Identifier::class, IdentifierType::Email),
				'credential' => new CredentialSchema(Credential::class, CredentialType::Password),
			])),
			new IdentifierProvider(
				new SqlIdentifierStore($connection),
			),
			new CredentialProvider(),
			new PasswordValidator(
				new SqlCredentialStore($connection),
				new BcryptHasher(),
			),
			new IdentityFromIdentifierProvider(
				new SqlIdentityStore($connection),
			),
			new Authenticator(
				new SqlSessionStore($connection),
				new SymmetricPasetoCodec($shared_key)
			),
		);
		$request = new ServerRequestFactory()
			->createServerRequest('POST', '/')
			->withParsedBody([
				'identifier' => 'test@example.com',
				'credential' => 'password',
			])
		;
		$rsp = $middleware->process(
			$request,
			new ClosureRequestHandler(function () {
				return new ResponseFactory()->createResponse();
			}),
		);

		expect($rsp->getStatusCode())
			->toBe(200)
			->and($rsp->getReasonPhrase())
				->toBe('OK')
		;
	});
});
