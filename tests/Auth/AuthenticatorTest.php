<?php

use Illuminate\Database\Capsule\Manager as CapsuleManager;
use NaN\Authentication\Codecs\SymmetricPasetoCodec;
use NaN\Authentication\Credentials\Credential;
use NaN\Authentication\CredentialType;
use NaN\Authentication\Hashers\BcryptHasher;
use NaN\Authentication\Identifiers\Identifier;
use NaN\Authentication\IdentifierType;
use NaN\Authentication\Middleware\Authenticator;
use NaN\Authentication\Middleware\Factors\Password\PasswordValidator;
use NaN\Authentication\Middleware\Providers\CredentialProvider;
use NaN\Authentication\Middleware\Providers\IdentifierProvider;
use NaN\Authentication\Schemas\CredentialSchema;
use NaN\Authentication\Schemas\IdentifierSchema;
use NaN\Authentication\Stores\Sql\SqlCredentialStore;
use NaN\Authentication\Stores\Sql\SqlIdentifierStore;
use NaN\Authentication\Stores\Sql\SqlIdentityStore;
use NaN\Authentication\Stores\Sql\SqlSessionStore;
use NaN\Database\Sql\SqlConnection;
use NaN\Http\RequestHandlers\ClosureRequestHandler;
use NaN\Http\RequestValidators\PostRequestValidator;
use NaN\Http\ResponseFactory;
use NaN\Http\ServerRequestFactory;
use Nette\Schema\Expect;

describe('Authenticator', function () {
	test('Password middleware', function () {
		$pdo = CapsuleManager::connection()->getPdo();
		$connection = new SqlConnection($pdo);
		$shared_key = \random_bytes(32);
		$middleware = new MiddlewareCollection(
			new IdentifierProvider(
				new PostRequestValidator(Expect::array([
					'identifier' => new IdentifierSchema(Identifier::class, IdentifierType::Email),
				])),
				new SqlIdentifierStore($connection),
			),
			new CredentialProvider(
				new PostRequestValidator(Expect::array([
					'credential' => new CredentialSchema(Credential::class, CredentialType::Password),
				])),
			),
			new PasswordValidator(
				new SqlIdentityStore($connection),
				new SqlCredentialStore($connection),
				new BcryptHasher(),
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
				return new ResponseFactory()->createResponse(200);
			}),
		);

		expect($rsp->getStatusCode())->toBe(200);
	});
});
