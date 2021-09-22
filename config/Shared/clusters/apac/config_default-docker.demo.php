<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'MY' => 'en_US',
    'HK' => 'en_US',
    'AU' => 'en_US',
    'NZ' => 'en_US',
    'PH' => 'en_US',
    'TH' => 'en_US',
    'MO' => 'en_US',
];
//"originKeys": {
//    "https://mo.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9tby5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.1HoOsE0xmeHIOMFXZK41RU4qmukVNTc2Z47NQ2l2gpU",
//    "https://ph.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9waC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.qGcQ6Pp7Rl_qc0jsJtaCkGVbWpfGxabrlJ-g5QgFDqQ",
//    "https://my.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9teS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.kLe9RcuOkoNK3hxcpBdUAimdIb4xi5I338pM3_-j6eU",
//    "https://hk.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9oay5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.lMjdQDp2aRcE5sjmFzNvrQnNhS1_gMBfcYSvk21pXkI",
//    "https://nz.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9uei5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.N_X8UnEXiF7EJZ0LpC2LTLn3NpGHzsS6v_UFJaMho0Y",
//    "https://au.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9hdS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.aeKjLbgsk074Xyhp0BTmkVsoRCk2PVRr9MPUVgcRYA0",
//    "https://th.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly90aC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.XAJZN9UAsbL43yk6f3rg8nEN_SU2xsEuB2vOsPprpNs"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'MY' => 'pub.v2.8216083088630330.aHR0cHM6Ly9teS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.kLe9RcuOkoNK3hxcpBdUAimdIb4xi5I338pM3_-j6eU',
        'HK' => 'pub.v2.8216083088630330.aHR0cHM6Ly9oay5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.lMjdQDp2aRcE5sjmFzNvrQnNhS1_gMBfcYSvk21pXkI',
        'AU' => 'pub.v2.8216083088630330.aHR0cHM6Ly9hdS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.aeKjLbgsk074Xyhp0BTmkVsoRCk2PVRr9MPUVgcRYA0',
        'NZ' => 'pub.v2.8216083088630330.aHR0cHM6Ly9uei5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.N_X8UnEXiF7EJZ0LpC2LTLn3NpGHzsS6v_UFJaMho0Y',
        'PH' => 'pub.v2.8216083088630330.aHR0cHM6Ly9waC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.qGcQ6Pp7Rl_qc0jsJtaCkGVbWpfGxabrlJ-g5QgFDqQ',
        'TH' => 'pub.v2.8216083088630330.aHR0cHM6Ly90aC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.XAJZN9UAsbL43yk6f3rg8nEN_SU2xsEuB2vOsPprpNs',
        'MO' => 'pub.v2.8216083088630330.aHR0cHM6Ly9tby5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.1HoOsE0xmeHIOMFXZK41RU4qmukVNTc2Z47NQ2l2gpU',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
