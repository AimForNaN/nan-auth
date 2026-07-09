<?php

namespace NaN\Authentication;

enum IdentifierType: string {
	case Email = 'email';
	case Oidc = 'oidc';
	case Phone = 'phone';
	case Username = 'username';
}
