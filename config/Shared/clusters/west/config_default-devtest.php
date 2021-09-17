<?php
// config file for overriding by cluster


use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'GB' => 'en_GB',
    'BE' => [
        'nl_BE',
        'fr_BE',
    ],
    'IE' => 'en_IE',
    'NL' => 'nl_NL',
];
