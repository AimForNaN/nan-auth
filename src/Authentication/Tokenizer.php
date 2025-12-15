<?php

namespace NaN\Authentication;

use NaN\Authentication\Tokenizer\Interfaces\TokenizerInterface;
use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\Keys\Base\SymmetricKey;
use ParagonIE\Paseto\Protocol\Version4;

class Tokenizer implements TokenizerInterface {
	public function __construct(
		protected $shared_key,
	) {
	}

	/**
	 * @throws PasetoException
	 * @throws \SodiumException
	 */
	public function decode(string $data): string {
		return Version4::decrypt($data, new SymmetricKey($this->shared_key));
	}

	/**
	 * @throws PasetoException
	 */
	public function encode(string $data): string {
		return Version4::encrypt($data, new SymmetricKey($this->shared_key));
	}
}
