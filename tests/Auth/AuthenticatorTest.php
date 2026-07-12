<?php

use Illuminate\Database\Capsule\Manager as CapsuleManager;
use NaN\Authentication\Codecs\SymmetricPasetoCodec;
use NaN\Authentication\Credentials\Credential;
use NaN\Authentication\CredentialType;
use NaN\Authentication\Factors\PasswordFactor;
use NaN\Authentication\Hashers\BcryptHasher;
use NaN\Authentication\Identifiers\Identifier;
use NaN\Authentication\IdentifierType;
use NaN\Authentication\Middleware\Authenticator;
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
use Psr\Http\Message\ServerRequestInterface;

describe('Authenticator', function () {
	it('Middleware', function () {
		$pdo = CapsuleManager::connection()->getPdo();
		$connection = new SqlConnection($pdo);
		$shared_key = \random_bytes(64);
		$auth = new Authenticator(
			new PasswordFactor(
				new PostRequestValidator(Expect::array([
					'identifier' => new IdentifierSchema(Identifier::class, IdentifierType::Email),
					'credential' => new CredentialSchema(Credential::class, CredentialType::Password),
				])),
				new SqlIdentityStore($connection),
				new SqlIdentifierStore($connection),
				new SqlCredentialStore($connection),
				new BcryptHasher(),
			),
			new SqlSessionStore($connection),
			new SymmetricPasetoCodec($shared_key)
		);
		$request = new ServerRequestFactory()->createServerRequest('POST', '/')
			->withParsedBody([
				'identifier' => 'test@example.com',
				'credential' => 'password',
			])
		;
		$rsp = $auth->process($request, new ClosureRequestHandler(function (ServerRequestInterface $req) {
			return new ResponseFactory()->createResponse(200);
		}));

		expect($rsp->getStatusCode())->toBe(200);
	});
});
