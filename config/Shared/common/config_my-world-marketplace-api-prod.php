<?php

use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

// ----------------------------------------------------------------------------
// ---------------------- MyWorld Marketplace API -----------------------------
// ----------------------------------------------------------------------------

// >>> MyWorld Marketplace API

$config[MyWorldMarketplaceApiConstants::API_URL] = getenv('MY_WORLD_MARKETPLACE_API_API_URL');
$config[MyWorldMarketplaceApiConstants::TOKEN_URL] = getenv('MY_WORLD_MARKETPLACE_API_TOKEN_URL');
$config[MyWorldMarketplaceApiConstants::CLIENT_ID] = getenv('MY_WORLD_MARKETPLACE_API_CLIENT_ID');
$config[MyWorldMarketplaceApiConstants::CLIENT_SECRET] = getenv('MY_WORLD_MARKETPLACE_API_CLIENT_SECRET');
$config[MyWorldMarketplaceApiConstants::SCOPE] = getenv('MY_WORLD_MARKETPLACE_API_SCOPE');
$config[MyWorldMarketplaceApiConstants::USER_AGENT] = 'Spryker/202009.0';
$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'BA3E82A7-BBC4-4874-A383-AA3100985CC9';
$config[MyWorldMarketplaceApiConstants::DEALER_ID_COUNTRY_MAP] = [
    'AT' => 'BA3E82A7-BBC4-4874-A383-AA3100985CC9',
    'PL' => '681aca46-da57-4337-8acc-adc6008db275',
    'CH' => '946105EA-A9E3-43D4-BF8E-AA3000CEFDF0',
    'NO' => '8F8D1A16-E266-4906-9528-AA310068B044',
    'SI' => 'EF06C080-96B6-4E9A-900F-AA310069C71E',
    'IT' => '5AD45F03-6374-4FA6-B2A0-AA3000CCD79E',
    'DE' => '99A2CD28-6523-413B-B6D0-AA31008C40DA',
    'CZ' => 'B0F730BC-186A-45AA-A600-AAB00084D1F3',
    'SK' => '338856FD-4D6C-40B5-8117-AAB0008676D1',
    'HU' => '5E39BA6C-48BC-4F6D-B6AB-AAB000CD695E',
    'DK' => 'CB3E0E45-0AFB-446C-ADAB-AABF00D82419',
    'EE' => '0B13941A-CFE5-4523-BD45-AABF00CACC73',
    'ES' => '58FBDDD4-CD67-4F9E-96A1-AAC000564B0E',
    'FI' => '5596F71E-BBF5-4D46-AE39-AABF00CD099D',
    'LT' => 'D33F3213-412C-44DC-B0B3-AABF00D1A309',
    'LV' => 'D57AF481-8062-4BDB-92B1-AABF00CF8F95',
    'PT' => '126ccf87-0d0e-4093-bacd-aabf00c8fd48',
    'RO' => '86A1BBD6-B982-4BE7-B87C-AAC0005791EE',
    'SE' => 'd7fd22e2-09f8-42bc-bfab-aabf00dfba98',
    'INT' => 'c7f1a149-0367-4c3a-b307-ab97009b3b38',
    'US' => '638cc7c3-afd5-4bcd-9bd1-ac3d008f43bb',
    'MY' => 'a6377ab0-d487-4396-a9a7-ac3f00cfe3c9',
    'UK' => 'cdb8c19d-7443-424b-8c31-acc3009d7fc7',
];
