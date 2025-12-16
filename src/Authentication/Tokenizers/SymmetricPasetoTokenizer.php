<?php

namespace NaN\Authentication\Tokenizers;

use NaN\Authentication\Tokenizers\Interfaces\TokenizerInterface;
use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\Keys\Base\SymmetricKey;
use ParagonIE\Paseto\Protocol\Version4;

class SymmetricPasetoTokenizer implements TokenizerInterface {
	public function __construct(
		protected readonly string $shared_key,
	) {
	}

	/**
	 * @throws PasetoException
	 * @throws \SodiumException
	 */
	public function decode(string $data): mixed {
		return \json_decode(Version4::decrypt($data, new SymmetricKey($this->shared_key)));
	}

	/**
	 * @throws PasetoException
	 */
	public function encode(mixed $data): string {
		return Version4::encrypt(\json_encode($data), new SymmetricKey($this->shared_key));
	}
}
