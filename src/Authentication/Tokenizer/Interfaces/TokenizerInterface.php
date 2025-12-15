<?php

namespace NaN\Authentication\Tokenizer\Interfaces;

interface TokenizerInterface {
	public function decode(string $data): string;
	public function encode(string $data): string;
}
