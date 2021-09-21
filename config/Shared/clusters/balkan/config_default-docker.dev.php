<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'SI' => 'sl_SI',
    'HR' => 'hr_HR',
    'BA' => 'bs_BA',
    'RS' => 'sr_RS',
    'ME' => 'sr_ME',
];
//"originKeys": {
//    "https://yves.hr.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmhyLm15d29ybGQubG9jYWw.tyey-Vc4pVMouj6SNbfCtzGY9ECv2huA2pGlCAalII8",
//    "https://yves.me.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm1lLm15d29ybGQubG9jYWw.zjrDLWAEtBFO55OlwpJ2DpvIpw0y3e03AOMlldqMQJc",
//    "https://yves.rs.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnJzLm15d29ybGQubG9jYWw.uvChPNMHcr-9IWLvisq3K3NMpj0wfNMmPCXMsdwAhAI",
//    "https://yves.si.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNpLm15d29ybGQubG9jYWw.Ru_3oOZgoM8kwcFwRLk4gUeHfCYCWiZ7IZINrpl4rVQ",
//    "https://yves.ba.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmJhLm15d29ybGQubG9jYWw.UfMhElu_19Q-jfq4QshKeem_3dgrBDI_A5Lv9pHEASE"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'SI' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNpLm15d29ybGQubG9jYWw.Ru_3oOZgoM8kwcFwRLk4gUeHfCYCWiZ7IZINrpl4rVQ',
        'HR' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmhyLm15d29ybGQubG9jYWw.tyey-Vc4pVMouj6SNbfCtzGY9ECv2huA2pGlCAalII8',
        'BA' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmJhLm15d29ybGQubG9jYWw.UfMhElu_19Q-jfq4QshKeem_3dgrBDI_A5Lv9pHEASE',
        'RS' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnJzLm15d29ybGQubG9jYWw.uvChPNMHcr-9IWLvisq3K3NMpj0wfNMmPCXMsdwAhAI',
        'ME' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm1lLm15d29ybGQubG9jYWw.zjrDLWAEtBFO55OlwpJ2DpvIpw0y3e03AOMlldqMQJc',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
