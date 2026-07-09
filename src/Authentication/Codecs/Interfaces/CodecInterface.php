<?php

namespace NaN\Authentication\Codecs\Interfaces;

interface CodecInterface {
	public function decode(string $data): mixed;
	public function encode(mixed $data): string;
}
