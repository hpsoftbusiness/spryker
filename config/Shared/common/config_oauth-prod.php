<?php

use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\Oauth\OauthConstants;

// ----------------------------------------------------------------------------
// ------------------------------ AUTHENTICATION ------------------------------
// ----------------------------------------------------------------------------

// >>> OAUTH

$config[OauthConstants::PRIVATE_KEY_PATH] = 'file://' . APPLICATION_ROOT_DIR . '/config/Zed/dev_only_private.key';
$config[OauthConstants::PUBLIC_KEY_PATH] = 'file://' . APPLICATION_ROOT_DIR . '/config/Zed/dev_only_public.key';
$config[OauthConstants::ENCRYPTION_KEY] = getenv('OAUTH_ENCRYPTION_KEY') ?: 'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen';
$config[OauthConstants::OAUTH_CLIENT_IDENTIFIER] = getenv('OAUTH_CLIENT_IDENTIFIER') ?: 'frontend';
$config[OauthConstants::OAUTH_CLIENT_SECRET] = getenv('OAUTH_CLIENT_SECRET') ?: 'abc123';

$config[AuthConstants::AUTH_DEFAULT_CREDENTIALS]['yves_system']['token'] = getenv('OAUTH_TOKEN') ?: 'tSTN3I4CZvF7OaIRLHupuC4KKD7gwp13fbty6gQjlsVDtphXn13xAs3Kh2TVpDBrtLtB3CGjkcnc66UJ';
