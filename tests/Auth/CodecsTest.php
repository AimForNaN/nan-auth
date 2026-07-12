<?php

use NaN\Authentication\Codecs\{
	SymmetricPasetoCodec,
};

describe('Codecs', function () {
	test('Paseto', function () {
		$shared_key = \random_bytes(32);
		$data = \bin2hex(\random_bytes(32));
		$codec = new SymmetricPasetoCodec($shared_key);
		$token = $codec->encode($data);

		expect($codec->decode($token))->toBe($data);
	});
});
