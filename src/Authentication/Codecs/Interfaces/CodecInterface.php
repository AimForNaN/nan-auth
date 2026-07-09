<?php

namespace NaN\Authentication\Tokenizers\Interfaces;

interface TokenizerInterface {
	public function decode(string $data): mixed;
	public function encode(mixed $data): string;
}
