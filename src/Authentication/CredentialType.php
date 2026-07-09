<?php

namespace NaN\Authentication;

enum CredentialType: string {
	case Challenge = 'challenge';
	case CrossSiteRequestForgeryToken = 'csrf_token';
	case OneTimePassword  = 'otp';
	case PassKey = 'passkey';
	case Password = 'password';
	case PublicKey = 'public_key';

	// Aliases!
	const CredentialType CsrfToken = self::CrossSiteRequestForgeryToken;
	const CredentialType Otp = self::OneTimePassword;
}
