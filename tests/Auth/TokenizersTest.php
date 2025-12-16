<?php

use NaN\Authentication\Tokenizers\{
	SymmetricJwtTokenizer,
	SymmetricPasetoTokenizer,
};

describe('Tokenizers', function () {
	it('Jwt', function () {
		$shared_key = \random_bytes(32);
		$data = [\bin2hex(\random_bytes(32))];
		$tokenizer = new SymmetricJwtTokenizer($shared_key);
		$token = $tokenizer->encode($data);

		expect($tokenizer->decode($token))->toBe($data);
	});

	it('Paseto', function () {
		$shared_key = \random_bytes(32);
		$data = \bin2hex(\random_bytes(32));
		$tokenizer = new SymmetricPasetoTokenizer($shared_key);
		$token = $tokenizer->encode($data);

		expect($tokenizer->decode($token))->toBe($data);
	});
});
