<?php

namespace NaN\Authentication\Codecs\Interfaces;

interface CodecInterface {
	public function decode(string $data): string;
	public function encode(string $data): string;
}
