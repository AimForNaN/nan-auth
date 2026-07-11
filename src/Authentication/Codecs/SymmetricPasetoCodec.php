<?php

namespace NaN\Authentication\Codecs;

use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\Keys\Base\SymmetricKey;
use ParagonIE\Paseto\Protocol\Version4;

class SymmetricPasetoCodec implements Interfaces\CodecInterface {
	public function __construct(
		protected readonly string $shared_key,
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
