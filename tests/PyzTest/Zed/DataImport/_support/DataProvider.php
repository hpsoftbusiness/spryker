<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\_support;

use ArrayObject;
use Generated\Shared\Transfer\TestAttributeDataImportTransfer;
use Generated\Shared\Transfer\TestImageSetDataImportTransfer;
use Generated\Shared\Transfer\TestOfferDataImportTransfer;
use Generated\Shared\Transfer\TestPriceDataImportTransfer;
use Generated\Shared\Transfer\TestProductAbstractDataImportTransfer;
use Generated\Shared\Transfer\TestProductConcreteDataImportTransfer;
use Generated\Shared\Transfer\TestStockDataImportTransfer;

class DataProvider
{
    private const PRICE_TYPE_DEFAULT = 'DEFAULT';
    private const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    //  First abstract product data from data/import/common/DE/combined_product_local.csv
    private const PRODUCT_ABSTRACT_NAME_EN = 'EliteClub Women`s T-Shirt in black with clear rhinestones';
    private const PRODUCT_ABSTRACT_NAME_DE = 'EliteClub Damen T-Shirt schwarz mit durchsichtigen Strasssteinen';
    private const PRODUCT_ABSTRACT_SKU = '0001';
    private const PRODUCT_ABSTRACT_STORE = 'DE';
    private const PRODUCT_ABSTRACT_PRICE_GROSS_ORIGINAL = 16990;
    private const PRODUCT_ABSTRACT_PRICE_GROSS_DEFAULT = 18990;
    private const PRODUCT_ABSTRACT_PRICE_NET_ORIGINAL = null;
    private const PRODUCT_ABSTRACT_PRICE_NET_DEFAULT = 6500;
    private const PRODUCT_ABSTRACT_PRICE_CURRENCY = 'EUR';
    private const PRODUCT_ABSTRACT_IMAGE_URL_LARGE = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_ABSTRACT_IMAGE_URL_SMALL = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_ABSTRACT_ATTRIBUTES = [
        'gender' => 'Female',
        'sellable_at' => true,
        'sellable_cz' => true,
        'sellable_fi' => true,
        'sellable_de' => true,
        'sellable_hu' => true,
        'sellable_it' => true,
        'sellable_no' => true,
        'sellable_pl' => true,
        'sellable_pt' => true,
        'sellable_ch' => true,
        'sellable_sk' => true,
        'sellable_si' => true,
        'sellable_se' => true,
        'sellable_be' => true,
        'sellable_bg' => true,
        'sellable_hr' => true,
        'sellable_cy' => true,
        'sellable_dk' => true,
        'sellable_ee' => true,
        'sellable_fr' => true,
        'sellable_gr' => true,
        'sellable_im' => true,
        'sellable_lv' => true,
        'sellable_lt' => true,
        'sellable_lu' => true,
        'sellable_mt' => true,
        'sellable_mc' => true,
        'sellable_nl' => true,
        'sellable_ro' => true,
        'sellable_es' => true,
        'sellable_gb' => true,
        'customer_group_1' => false,
        'customer_group_2' => false,
        'customer_group_3' => true,
        'customer_group_4' => true,
        'customer_group_5' => true,
        'manufacturer' => 'myWorld',
        'brand' => 'EliteClub',
        'benefit_amount' => '30',
        'benefit_store' => true,
        'shopping_point_store' => false,
        'cashback_amount' => 3,
        'shopping_points' => 2,
    ];
// Concrete product data for first abstract product from data/import/common/DE/combined_product_local.csv
    private const PRODUCT_CONCRETE_1_NAME_EN = 'EliteClub Women`s T-Shirt in black with clear rhinestones';
    private const PRODUCT_CONCRETE_1_NAME_DE = 'EliteClub Damen T-Shirt schwarz mit durchsichtigen Strasssteinen';
    private const PRODUCT_CONCRETE_1_SKU = '9120050580091-0001';
    private const PRODUCT_CONCRETE_1_IS_ACTIVE = true;
    private const PRODUCT_CONCRETE_1_PRICE_GROSS_ORIGINAL = 16990;
    private const PRODUCT_CONCRETE_1_PRICE_GROSS_DEFAULT = 16990;
    private const PRODUCT_CONCRETE_1_PRICE_NET_ORIGINAL = null;
    private const PRODUCT_CONCRETE_1_PRICE_NET_DEFAULT = 6500;
    private const PRODUCT_CONCRETE_1_PRICE_CURRENCY = 'EUR';
    private const PRODUCT_CONCRETE_1_IMAGE_URL_LARGE = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_1_IMAGE_URL_SMALL = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_1_STOCK_IS_NEVER_OUT_OF = false;
    private const PRODUCT_CONCRETE_1_STOCK_QUANTITY = 10;
    private const PRODUCT_CONCRETE_1_ATTRIBUTES = [
        'mpn' => 'ELITEMDL14',
        'ean' => '9120050580091',
        'gtin' => '09120050580091',
        'taric_code' => '61091000',
        'color' => 'Schwarz',
        'size' => 'L',
        'gender' => 'Female',
        'sellable_at' => true,
        'sellable_cz' => true,
        'sellable_fi' => true,
        'sellable_de' => true,
        'sellable_hu' => true,
        'sellable_it' => true,
        'sellable_no' => true,
        'sellable_pl' => true,
        'sellable_pt' => true,
        'sellable_ch' => true,
        'sellable_sk' => true,
        'sellable_si' => true,
        'sellable_se' => true,
        'sellable_be' => true,
        'sellable_bg' => true,
        'sellable_hr' => true,
        'sellable_cy' => true,
        'sellable_dk' => true,
        'sellable_ee' => true,
        'sellable_fr' => true,
        'sellable_gr' => true,
        'sellable_im' => true,
        'sellable_lv' => true,
        'sellable_lt' => true,
        'sellable_lu' => true,
        'sellable_mt' => true,
        'sellable_mc' => true,
        'sellable_nl' => true,
        'sellable_ro' => true,
        'sellable_es' => true,
        'sellable_gb' => true,
        'customer_group_1' => false,
        'customer_group_2' => false,
        'customer_group_3' => true,
        'customer_group_4' => true,
        'customer_group_5' => true,
        'manufacturer' => 'myWorld',
        'brand' => 'EliteClub',
        'benefit_amount' => '30',
        'benefit_store' => true,
        'shopping_point_store' => false,
        'cashback_amount' => 3,
        'shopping_points' => 2,

    ];

// Concrete product data for first abstract product from data/import/common/DE/combined_product_local.csv
    private const PRODUCT_CONCRETE_2_NAME_EN = 'EliteClub Women`s T-Shirt in black with clear rhinestones';
    private const PRODUCT_CONCRETE_2_NAME_DE = 'EliteClub Damen T-Shirt schwarz mit durchsichtigen Strasssteinen';
    private const PRODUCT_CONCRETE_2_SKU = '9120050580084-0001';
    private const PRODUCT_CONCRETE_2_IS_ACTIVE = true;
    private const PRODUCT_CONCRETE_2_PRICE_GROSS_ORIGINAL = 16990;
    private const PRODUCT_CONCRETE_2_PRICE_GROSS_DEFAULT = 16990;
    private const PRODUCT_CONCRETE_2_PRICE_NET_ORIGINAL = null;
    private const PRODUCT_CONCRETE_2_PRICE_NET_DEFAULT = 6500;
    private const PRODUCT_CONCRETE_2_PRICE_CURRENCY = 'EUR';
    private const PRODUCT_CONCRETE_2_IMAGE_URL_LARGE = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_2_IMAGE_URL_SMALL = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_2_STOCK_IS_NEVER_OUT_OF = false;
    private const PRODUCT_CONCRETE_2_STOCK_QUANTITY = 15;
    private const PRODUCT_CONCRETE_2_ATTRIBUTES = [
        'mpn' => 'ELITEMDL13',
        'ean' => '9120050580084',
        'gtin' => '09120050580084',
        'taric_code' => '61091000',
        'color' => 'Schwarz',
        'size' => 'M',
        'gender' => 'Female',
        'sellable_at' => true,
        'sellable_cz' => true,
        'sellable_fi' => true,
        'sellable_de' => true,
        'sellable_hu' => true,
        'sellable_it' => true,
        'sellable_no' => true,
        'sellable_pl' => true,
        'sellable_pt' => true,
        'sellable_ch' => true,
        'sellable_sk' => true,
        'sellable_si' => true,
        'sellable_se' => true,
        'sellable_be' => true,
        'sellable_bg' => true,
        'sellable_hr' => true,
        'sellable_cy' => true,
        'sellable_dk' => true,
        'sellable_ee' => true,
        'sellable_fr' => true,
        'sellable_gr' => true,
        'sellable_im' => true,
        'sellable_lv' => true,
        'sellable_lt' => true,
        'sellable_lu' => true,
        'sellable_mt' => true,
        'sellable_mc' => true,
        'sellable_nl' => true,
        'sellable_ro' => true,
        'sellable_es' => true,
        'sellable_gb' => true,
        'customer_group_1' => false,
        'customer_group_2' => false,
        'customer_group_3' => true,
        'customer_group_4' => true,
        'customer_group_5' => true,
        'manufacturer' => 'myWorld',
        'brand' => 'EliteClub',
        'benefit_amount' => '30',
        'benefit_store' => true,
        'shopping_point_store' => false,
        'cashback_amount' => 3,
        'shopping_points' => 2,
    ];

// Concrete product data for first abstract product from data/import/common/DE/combined_product_local.csv
    private const PRODUCT_CONCRETE_3_NAME_EN = 'EliteClub Women`s T-Shirt in black with clear rhinestones';
    private const PRODUCT_CONCRETE_3_NAME_DE = 'EliteClub Damen T-Shirt schwarz mit durchsichtigen Strasssteinen';
    private const PRODUCT_CONCRETE_3_SKU = '9120050580077-0001';
    private const PRODUCT_CONCRETE_3_IS_ACTIVE = true;
    private const PRODUCT_CONCRETE_3_PRICE_GROSS_ORIGINAL = 16990;
    private const PRODUCT_CONCRETE_3_PRICE_GROSS_DEFAULT = 16990;
    private const PRODUCT_CONCRETE_3_PRICE_NET_ORIGINAL = null;
    private const PRODUCT_CONCRETE_3_PRICE_NET_DEFAULT = 6500;
    private const PRODUCT_CONCRETE_3_PRICE_CURRENCY = 'EUR';
    private const PRODUCT_CONCRETE_3_IMAGE_URL_LARGE = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_3_IMAGE_URL_SMALL = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_3_STOCK_IS_NEVER_OUT_OF = false;
    private const PRODUCT_CONCRETE_3_STOCK_QUANTITY = 10;
    private const PRODUCT_CONCRETE_3_ATTRIBUTES = [
        'mpn' => 'ELITEMDL12',
        'ean' => '9120050580077',
        'gtin' => '09120050580077',
        'taric_code' => '61091000',
        'color' => 'Schwarz',
        'size' => 'S',
        'gender' => 'Female',
        'sellable_at' => true,
        'sellable_cz' => true,
        'sellable_fi' => true,
        'sellable_de' => true,
        'sellable_hu' => true,
        'sellable_it' => true,
        'sellable_no' => true,
        'sellable_pl' => true,
        'sellable_pt' => true,
        'sellable_ch' => true,
        'sellable_sk' => true,
        'sellable_si' => true,
        'sellable_se' => true,
        'sellable_be' => true,
        'sellable_bg' => true,
        'sellable_hr' => true,
        'sellable_cy' => true,
        'sellable_dk' => true,
        'sellable_ee' => true,
        'sellable_fr' => true,
        'sellable_gr' => true,
        'sellable_im' => true,
        'sellable_lv' => true,
        'sellable_lt' => true,
        'sellable_lu' => true,
        'sellable_mt' => true,
        'sellable_mc' => true,
        'sellable_nl' => true,
        'sellable_ro' => true,
        'sellable_es' => true,
        'sellable_gb' => true,
        'customer_group_1' => false,
        'customer_group_2' => false,
        'customer_group_3' => true,
        'customer_group_4' => true,
        'customer_group_5' => true,
        'manufacturer' => 'myWorld',
        'brand' => 'EliteClub',
        'benefit_amount' => '30',
        'benefit_store' => true,
        'shopping_point_store' => false,
        'cashback_amount' => 3,
        'shopping_points' => 2,
    ];

// Concrete product data for first abstract product from data/import/common/DE/combined_product_local.csv
    private const PRODUCT_CONCRETE_4_NAME_EN = 'EliteClub Women`s T-Shirt in black with clear rhinestones';
    private const PRODUCT_CONCRETE_4_NAME_DE = 'EliteClub Damen T-Shirt schwarz mit durchsichtigen Strasssteinen';
    private const PRODUCT_CONCRETE_4_SKU = '9120050580107-0001';
    private const PRODUCT_CONCRETE_4_IS_ACTIVE = true;
    private const PRODUCT_CONCRETE_4_PRICE_GROSS_ORIGINAL = 16990;
    private const PRODUCT_CONCRETE_4_PRICE_GROSS_DEFAULT = 16990;
    private const PRODUCT_CONCRETE_4_PRICE_NET_ORIGINAL = null;
    private const PRODUCT_CONCRETE_4_PRICE_NET_DEFAULT = 6500;
    private const PRODUCT_CONCRETE_4_PRICE_CURRENCY = 'EUR';
    private const PRODUCT_CONCRETE_4_IMAGE_URL_LARGE = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_4_IMAGE_URL_SMALL = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_4_STOCK_IS_NEVER_OUT_OF = false;
    private const PRODUCT_CONCRETE_4_STOCK_QUANTITY = 5;
    private const PRODUCT_CONCRETE_4_ATTRIBUTES = [
        'mpn' => 'ELITEMDL15',
        'ean' => '9120050580107',
        'gtin' => '09120050580107',
        'taric_code' => '61091000',
        'color' => 'Schwarz',
        'size' => 'XL',
        'gender' => 'Female',
        'sellable_at' => true,
        'sellable_cz' => true,
        'sellable_fi' => true,
        'sellable_de' => true,
        'sellable_hu' => true,
        'sellable_it' => true,
        'sellable_no' => true,
        'sellable_pl' => true,
        'sellable_pt' => true,
        'sellable_ch' => true,
        'sellable_sk' => true,
        'sellable_si' => true,
        'sellable_se' => true,
        'sellable_be' => true,
        'sellable_bg' => true,
        'sellable_hr' => true,
        'sellable_cy' => true,
        'sellable_dk' => true,
        'sellable_ee' => true,
        'sellable_fr' => true,
        'sellable_gr' => true,
        'sellable_im' => true,
        'sellable_lv' => true,
        'sellable_lt' => true,
        'sellable_lu' => true,
        'sellable_mt' => true,
        'sellable_mc' => true,
        'sellable_nl' => true,
        'sellable_ro' => true,
        'sellable_es' => true,
        'sellable_gb' => true,
        'customer_group_1' => false,
        'customer_group_2' => false,
        'customer_group_3' => true,
        'customer_group_4' => true,
        'customer_group_5' => true,
        'manufacturer' => 'myWorld',
        'brand' => 'EliteClub',
        'benefit_amount' => '30',
        'benefit_store' => true,
        'shopping_point_store' => false,
        'cashback_amount' => 3,
        'shopping_points' => 2,
    ];

// Concrete product data for first abstract product from data/import/common/DE/combined_product_local.csv
    private const PRODUCT_CONCRETE_5_NAME_EN = 'EliteClub Women`s T-Shirt in black with clear rhinestones';
    private const PRODUCT_CONCRETE_5_NAME_DE = 'EliteClub Damen T-Shirt schwarz mit durchsichtigen Strasssteinen';
    private const PRODUCT_CONCRETE_5_SKU = '9120050580060-0001';
    private const PRODUCT_CONCRETE_5_IS_ACTIVE = true;
    private const PRODUCT_CONCRETE_5_PRICE_GROSS_ORIGINAL = 16990;
    private const PRODUCT_CONCRETE_5_PRICE_GROSS_DEFAULT = 16990;
    private const PRODUCT_CONCRETE_5_PRICE_NET_ORIGINAL = null;
    private const PRODUCT_CONCRETE_5_PRICE_NET_DEFAULT = 6500;
    private const PRODUCT_CONCRETE_5_PRICE_CURRENCY = 'EUR';
    private const PRODUCT_CONCRETE_5_IMAGE_URL_LARGE = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_5_IMAGE_URL_SMALL = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_5_STOCK_IS_NEVER_OUT_OF = false;
    private const PRODUCT_CONCRETE_5_STOCK_QUANTITY = 5;
    private const PRODUCT_CONCRETE_5_ATTRIBUTES = [
        'mpn' => 'ELITEMDL11',
        'ean' => '9120050580060',
        'gtin' => '09120050580060',
        'taric_code' => '61091000',
        'color' => 'Schwarz',
        'size' => 'XS',
        'gender' => 'Female',
        'sellable_at' => true,
        'sellable_cz' => true,
        'sellable_fi' => true,
        'sellable_de' => true,
        'sellable_hu' => true,
        'sellable_it' => true,
        'sellable_no' => true,
        'sellable_pl' => true,
        'sellable_pt' => true,
        'sellable_ch' => true,
        'sellable_sk' => true,
        'sellable_si' => true,
        'sellable_se' => true,
        'sellable_be' => true,
        'sellable_bg' => true,
        'sellable_hr' => true,
        'sellable_cy' => true,
        'sellable_dk' => true,
        'sellable_ee' => true,
        'sellable_fr' => true,
        'sellable_gr' => true,
        'sellable_im' => true,
        'sellable_lv' => true,
        'sellable_lt' => true,
        'sellable_lu' => true,
        'sellable_mt' => true,
        'sellable_mc' => true,
        'sellable_nl' => true,
        'sellable_ro' => true,
        'sellable_es' => true,
        'sellable_gb' => true,
        'customer_group_1' => false,
        'customer_group_2' => false,
        'customer_group_3' => true,
        'customer_group_4' => true,
        'customer_group_5' => true,
        'manufacturer' => 'myWorld',
        'brand' => 'EliteClub',
        'benefit_amount' => '30',
        'benefit_store' => true,
        'shopping_point_store' => false,
        'cashback_amount' => 3,
        'shopping_points' => 2,
    ];

// Concrete product data for first abstract product from data/import/common/DE/combined_product_local.csv
    private const PRODUCT_CONCRETE_6_NAME_EN = 'EliteClub Women`s T-Shirt in black with clear rhinestones';
    private const PRODUCT_CONCRETE_6_NAME_DE = 'EliteClub Damen T-Shirt schwarz mit durchsichtigen Strasssteinen';
    private const PRODUCT_CONCRETE_6_SKU = '9120050580114-0001';
    private const PRODUCT_CONCRETE_6_IS_ACTIVE = true;
    private const PRODUCT_CONCRETE_6_PRICE_GROSS_ORIGINAL = 16990;
    private const PRODUCT_CONCRETE_6_PRICE_GROSS_DEFAULT = 16990;
    private const PRODUCT_CONCRETE_6_PRICE_NET_ORIGINAL = null;
    private const PRODUCT_CONCRETE_6_PRICE_NET_DEFAULT = 6500;
    private const PRODUCT_CONCRETE_6_PRICE_CURRENCY = 'EUR';
    private const PRODUCT_CONCRETE_6_IMAGE_URL_LARGE = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_6_IMAGE_URL_SMALL = 'https://l.mwscdn.io/large/marketplace/Content/Produktfotos/abstract_sku_0001.jpg';
    private const PRODUCT_CONCRETE_6_STOCK_IS_NEVER_OUT_OF = false;
    private const PRODUCT_CONCRETE_6_STOCK_QUANTITY = 5;
    private const PRODUCT_CONCRETE_6_ATTRIBUTES = [
        'mpn' => 'ELITEMDL16',
        'ean' => '9120050580114',
        'gtin' => '09120050580114',
        'taric_code' => '61091000',
        'color' => 'Schwarz',
        'size' => 'XXL',
        'gender' => 'Female',
        'sellable_at' => true,
        'sellable_cz' => true,
        'sellable_fi' => true,
        'sellable_de' => true,
        'sellable_hu' => true,
        'sellable_it' => true,
        'sellable_no' => true,
        'sellable_pl' => true,
        'sellable_pt' => true,
        'sellable_ch' => true,
        'sellable_sk' => true,
        'sellable_si' => true,
        'sellable_se' => true,
        'sellable_be' => true,
        'sellable_bg' => true,
        'sellable_hr' => true,
        'sellable_cy' => true,
        'sellable_dk' => true,
        'sellable_ee' => true,
        'sellable_fr' => true,
        'sellable_gr' => true,
        'sellable_im' => true,
        'sellable_lv' => true,
        'sellable_lt' => true,
        'sellable_lu' => true,
        'sellable_mt' => true,
        'sellable_mc' => true,
        'sellable_nl' => true,
        'sellable_ro' => true,
        'sellable_es' => true,
        'sellable_gb' => true,
        'customer_group_1' => false,
        'customer_group_2' => false,
        'customer_group_3' => true,
        'customer_group_4' => true,
        'customer_group_5' => true,
        'manufacturer' => 'myWorld',
        'brand' => 'EliteClub',
        'benefit_amount' => '30',
        'benefit_store' => true,
        'shopping_point_store' => false,
        'cashback_amount' => 3,
        'shopping_points' => 2,
    ];

    //  Last affiliate product data from data/import/common/DE/combined_product_local.csv
    private const PRODUCT_AFFILIATE_NAME_EN = 'goldbuch Babyalbum - Whale Serenity';
    private const PRODUCT_AFFILIATE_NAME_DE = 'goldbuch Babyalbum - Whale Serenity';
    private const PRODUCT_AFFILIATE_ABSTRACT_STORE = 'DE';
    private const PRODUCT_AFFILIATE_ABSTRACT_SKU = 'A14824_A262400';
    private const PRODUCT_AFFILIATE_CONCRETE_SKU = '14824_A262400';
    private const PRODUCT_AFFILIATE_PRICE_GROSS_ORIGINAL = null;
    private const PRODUCT_AFFILIATE_PRICE_GROSS_DEFAULT = 2264;
    private const PRODUCT_AFFILIATE_PRICE_NET_ORIGINAL = null;
    private const PRODUCT_AFFILIATE_PRICE_NET_DEFAULT = 2264;
    private const PRODUCT_AFFILIATE_PRICE_CURRENCY = 'EUR';
    private const PRODUCT_AFFILIATE_IMAGE_URL_LARGE = 'https://cdn.babymarkt.com/babymarkt/mainimage/A262400/390/goldbuch-Babyalbum-Whale-Serenity.jpg';
    private const PRODUCT_AFFILIATE_IMAGE_URL_SMALL = 'https://cdn.babymarkt.com/babymarkt/mainimage/A262400/390/goldbuch-Babyalbum-Whale-Serenity.jpg';
    private const PRODUCT_AFFILIATE_STOCK_IS_NEVER_OUT_OF = false;
    private const PRODUCT_AFFILIATE_STOCK_QUANTITY = 1;
    private const PRODUCT_AFFILIATE_IS_ACTIVE = true;
    private const PRODUCT_AFFILIATE_ATTRIBUTES = [
        'ean' => '4009835154649',
        'sellable_at' => true,
        'sellable_cz' => false,
        'sellable_fi' => false,
        'sellable_de' => true,
        'sellable_hu' => false,
        'sellable_it' => false,
        'sellable_no' => false,
        'sellable_pl' => false,
        'sellable_pt' => false,
        'sellable_ch' => false,
        'sellable_sk' => false,
        'sellable_si' => false,
        'sellable_se' => false,
        'sellable_be' => false,
        'sellable_bg' => false,
        'sellable_hr' => false,
        'sellable_cy' => false,
        'sellable_dk' => false,
        'sellable_ee' => false,
        'sellable_fr' => false,
        'sellable_gr' => false,
        'sellable_im' => false,
        'sellable_lv' => false,
        'sellable_lt' => false,
        'sellable_lu' => false,
        'sellable_mt' => false,
        'sellable_mc' => false,
        'sellable_nl' => false,
        'sellable_ro' => false,
        'sellable_es' => false,
        'sellable_gb' => false,
        'customer_group_1' => true,
        'customer_group_2' => true,
        'customer_group_3' => true,
        'customer_group_4' => true,
        'customer_group_5' => true,
        'brand' => 'Goldbuch',
        'delivery_cost' => '4.95',
        'delivery_time' => 'ca. 3-6 Werktage',
        'affiliate_availability' => 'DE',
    ];
    private const PRODUCT_AFFILIATE_DATA_ATTRIBUTES = [
        "affiliate_product" => 'TRUE',
        'affiliate_deeplink' => 'https://www.awin1.com/pclick.php?p=24598603105&a=333885&m=14824',
        'displayed_price' => '22.64',
        'affiliate_merchant_name' => 'babymarkt DE',
        'affiliate_merchant_id' => '14824',
        'merchant_product_id' => 'A262400',
    ];
    // Offer data are getting from data/import/common/common/marketplace/.. by sku
    private const OFFER_MERCHANT_REFERENCE_1 = 'MER000001';
    private const OFFER_MERCHANT_SKU_1 = 'MER000001-119';
    private const OFFER_MERCHANT_STORE_1 = 'DE';
    private const OFFER_MERCHANT_STORE_1_PRICE_GROSS_ORIGINAL = 323800;
    private const OFFER_MERCHANT_STORE_1_PRICE_GROSS_DEFAULT = 206800;
    private const OFFER_MERCHANT_STORE_1_PRICE_NET_ORIGINAL = 323800;
    private const OFFER_MERCHANT_STORE_1_PRICE_NET_DEFAULT = 206800;
    private const OFFER_MERCHANT_STORE_1_PRICE_CURRENCY = 'EUR';

    private const OFFER_AFFILIATE_DATA_1 = [
        'affiliate_product' => 'TRUE',
        'affiliate_deeplink' => 'https://www.awin1.com/pclick.php?p=26348632659&a=333885&m=14824',
        'displayed_price' => '5.00',

    ];
    private const OFFER_MERCHANT_REFERENCE_2 = 'MER000002';
    private const OFFER_MERCHANT_SKU_2 = 'MER000002-119';
    private const OFFER_MERCHANT_STORE_2 = 'DE';
    private const OFFER_MERCHANT_STORE_2_PRICE_GROSS_ORIGINAL = 324300;
    private const OFFER_MERCHANT_STORE_2_PRICE_GROSS_DEFAULT = 207300;
    private const OFFER_MERCHANT_STORE_2_PRICE_NET_ORIGINAL = 324300;
    private const OFFER_MERCHANT_STORE_2_PRICE_NET_DEFAULT = 207300;
    private const OFFER_MERCHANT_STORE_2_PRICE_CURRENCY = 'EUR';

    private const OFFER_AFFILIATE_DATA_2 = [
        'affiliate_product' => 'TRUE',
        'affiliate_deeplink' => 'https://www.awin1.com/pclick.php?p=24598631273&a=333885&m=14824',
        'displayed_price' => '5.00',

    ];

    /**
     * @return \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer
     */
    public function getProduct(): TestProductAbstractDataImportTransfer
    {
        $productAbstract = new TestProductAbstractDataImportTransfer();
        $productAbstract->setNameEN(self::PRODUCT_ABSTRACT_NAME_EN);
        $productAbstract->setNameDE(self::PRODUCT_ABSTRACT_NAME_DE);
        $productAbstract->setSku(self::PRODUCT_ABSTRACT_SKU);
        $productAbstract->setStore(self::PRODUCT_ABSTRACT_STORE);
        $productAbstract->setIsAffiliate(false);
        $productAbstract->setAttribute($this->getAttributeArrayObject(self::PRODUCT_ABSTRACT_ATTRIBUTES));

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_ABSTRACT_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_ABSTRACT_IMAGE_URL_SMALL);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_ABSTRACT_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_ABSTRACT_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_ABSTRACT_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_ABSTRACT_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_ABSTRACT_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_ABSTRACT_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productAbstract->setImage($imageSetDataImport);
        $productAbstract->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );
        $productAbstract->setProductConcrete(
            new ArrayObject(
                [
                    $this->getProductConcrete1(),
                    $this->getProductConcrete2(),
                    $this->getProductConcrete3(),
                    $this->getProductConcrete4(),
                    $this->getProductConcrete5(),
                    $this->getProductConcrete6(),
                ]
            )
        );

        return $productAbstract;
    }

    /**
     * @return \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer
     */
    public function getProductAffiliate(): TestProductAbstractDataImportTransfer
    {
        $productAbstract = new TestProductAbstractDataImportTransfer();
        $productAbstract->setNameEN(self::PRODUCT_AFFILIATE_NAME_EN);
        $productAbstract->setNameDE(self::PRODUCT_AFFILIATE_NAME_DE);
        $productAbstract->setSku(self::PRODUCT_AFFILIATE_ABSTRACT_SKU);
        $productAbstract->setStore(self::PRODUCT_AFFILIATE_ABSTRACT_STORE);
        $productAbstract->setIsAffiliate(true);
        $productAbstract->setAttribute($this->getAttributeArrayObject(self::PRODUCT_AFFILIATE_ATTRIBUTES));
        $productAbstract->setAttributeAffiliate(
            $this->getAttributeArrayObject(self::PRODUCT_AFFILIATE_DATA_ATTRIBUTES)
        );

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_AFFILIATE_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_AFFILIATE_IMAGE_URL_SMALL);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_AFFILIATE_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_AFFILIATE_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_AFFILIATE_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_AFFILIATE_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_AFFILIATE_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_AFFILIATE_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productAbstract->setImage($imageSetDataImport);
        $productAbstract->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );
        $productAbstract->setProductConcrete(
            new ArrayObject(
                [
                    $this->getProductConcreteAffiliate(),
                ]
            )
        );

        return $productAbstract;
    }

    /**
     * @return \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer
     */
    public function getProductConcrete1(): TestProductConcreteDataImportTransfer
    {
        $productConcrete = new TestProductConcreteDataImportTransfer();
        $productConcrete->setNameEN(self::PRODUCT_CONCRETE_1_NAME_EN);
        $productConcrete->setNameDE(self::PRODUCT_CONCRETE_1_NAME_DE);
        $productConcrete->setSku(self::PRODUCT_CONCRETE_1_SKU);
        $productConcrete->setIsActive(self::PRODUCT_CONCRETE_1_IS_ACTIVE);
        $productConcrete->setAttribute($this->getAttributeArrayObject(self::PRODUCT_CONCRETE_1_ATTRIBUTES));

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_CONCRETE_1_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_CONCRETE_1_IMAGE_URL_SMALL);

        $stockDataImport = new TestStockDataImportTransfer();
        $stockDataImport->setIsNeverOutOfStock(self::PRODUCT_CONCRETE_1_STOCK_IS_NEVER_OUT_OF);
        $stockDataImport->setQuantity(self::PRODUCT_CONCRETE_1_STOCK_QUANTITY);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_CONCRETE_1_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_CONCRETE_1_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_CONCRETE_1_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_CONCRETE_1_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_CONCRETE_1_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_CONCRETE_1_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productConcrete->setStock($stockDataImport);
        $productConcrete->setImage($imageSetDataImport);
        $productConcrete->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );

        return $productConcrete;
    }

    /**
     * @return \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer
     */
    public function getProductConcrete2(): TestProductConcreteDataImportTransfer
    {
        $productConcrete = new TestProductConcreteDataImportTransfer();
        $productConcrete->setNameEN(self::PRODUCT_CONCRETE_2_NAME_EN);
        $productConcrete->setNameDE(self::PRODUCT_CONCRETE_2_NAME_DE);
        $productConcrete->setSku(self::PRODUCT_CONCRETE_2_SKU);
        $productConcrete->setIsActive(self::PRODUCT_CONCRETE_2_IS_ACTIVE);
        $productConcrete->setAttribute($this->getAttributeArrayObject(self::PRODUCT_CONCRETE_2_ATTRIBUTES));

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_CONCRETE_2_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_CONCRETE_2_IMAGE_URL_SMALL);

        $stockDataImport = new TestStockDataImportTransfer();
        $stockDataImport->setIsNeverOutOfStock(self::PRODUCT_CONCRETE_2_STOCK_IS_NEVER_OUT_OF);
        $stockDataImport->setQuantity(self::PRODUCT_CONCRETE_2_STOCK_QUANTITY);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_CONCRETE_2_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_CONCRETE_2_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_CONCRETE_2_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_CONCRETE_2_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_CONCRETE_2_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_CONCRETE_2_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productConcrete->setStock($stockDataImport);
        $productConcrete->setImage($imageSetDataImport);
        $productConcrete->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );

        return $productConcrete;
    }

    /**
     * @return \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer
     */
    public function getProductConcrete3(): TestProductConcreteDataImportTransfer
    {
        $productConcrete = new TestProductConcreteDataImportTransfer();
        $productConcrete->setNameEN(self::PRODUCT_CONCRETE_3_NAME_EN);
        $productConcrete->setNameDE(self::PRODUCT_CONCRETE_3_NAME_DE);
        $productConcrete->setSku(self::PRODUCT_CONCRETE_3_SKU);
        $productConcrete->setIsActive(self::PRODUCT_CONCRETE_3_IS_ACTIVE);
        $productConcrete->setAttribute($this->getAttributeArrayObject(self::PRODUCT_CONCRETE_3_ATTRIBUTES));

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_CONCRETE_3_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_CONCRETE_3_IMAGE_URL_SMALL);

        $stockDataImport = new TestStockDataImportTransfer();
        $stockDataImport->setIsNeverOutOfStock(self::PRODUCT_CONCRETE_3_STOCK_IS_NEVER_OUT_OF);
        $stockDataImport->setQuantity(self::PRODUCT_CONCRETE_3_STOCK_QUANTITY);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_CONCRETE_3_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_CONCRETE_3_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_CONCRETE_3_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_CONCRETE_3_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_CONCRETE_3_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_CONCRETE_3_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productConcrete->setStock($stockDataImport);
        $productConcrete->setImage($imageSetDataImport);
        $productConcrete->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );

        return $productConcrete;
    }

    /**
     * @return \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer
     */
    public function getProductConcrete4(): TestProductConcreteDataImportTransfer
    {
        $productConcrete = new TestProductConcreteDataImportTransfer();
        $productConcrete->setNameEN(self::PRODUCT_CONCRETE_4_NAME_EN);
        $productConcrete->setNameDE(self::PRODUCT_CONCRETE_4_NAME_DE);
        $productConcrete->setSku(self::PRODUCT_CONCRETE_4_SKU);
        $productConcrete->setIsActive(self::PRODUCT_CONCRETE_4_IS_ACTIVE);
        $productConcrete->setAttribute($this->getAttributeArrayObject(self::PRODUCT_CONCRETE_4_ATTRIBUTES));

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_CONCRETE_4_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_CONCRETE_4_IMAGE_URL_SMALL);

        $stockDataImport = new TestStockDataImportTransfer();
        $stockDataImport->setIsNeverOutOfStock(self::PRODUCT_CONCRETE_4_STOCK_IS_NEVER_OUT_OF);
        $stockDataImport->setQuantity(self::PRODUCT_CONCRETE_4_STOCK_QUANTITY);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_CONCRETE_4_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_CONCRETE_4_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_CONCRETE_4_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_CONCRETE_4_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_CONCRETE_4_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_CONCRETE_4_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productConcrete->setStock($stockDataImport);
        $productConcrete->setImage($imageSetDataImport);
        $productConcrete->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );

        return $productConcrete;
    }

    /**
     * @return \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer
     */
    public function getProductConcrete5(): TestProductConcreteDataImportTransfer
    {
        $productConcrete = new TestProductConcreteDataImportTransfer();
        $productConcrete->setNameEN(self::PRODUCT_CONCRETE_5_NAME_EN);
        $productConcrete->setNameDE(self::PRODUCT_CONCRETE_5_NAME_DE);
        $productConcrete->setSku(self::PRODUCT_CONCRETE_5_SKU);
        $productConcrete->setIsActive(self::PRODUCT_CONCRETE_5_IS_ACTIVE);
        $productConcrete->setAttribute($this->getAttributeArrayObject(self::PRODUCT_CONCRETE_5_ATTRIBUTES));

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_CONCRETE_5_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_CONCRETE_5_IMAGE_URL_SMALL);

        $stockDataImport = new TestStockDataImportTransfer();
        $stockDataImport->setIsNeverOutOfStock(self::PRODUCT_CONCRETE_5_STOCK_IS_NEVER_OUT_OF);
        $stockDataImport->setQuantity(self::PRODUCT_CONCRETE_5_STOCK_QUANTITY);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_CONCRETE_5_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_CONCRETE_5_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_CONCRETE_5_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_CONCRETE_5_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_CONCRETE_5_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_CONCRETE_5_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productConcrete->setStock($stockDataImport);
        $productConcrete->setImage($imageSetDataImport);
        $productConcrete->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );

        return $productConcrete;
    }

    /**
     * @return \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer
     */
    public function getProductConcrete6(): TestProductConcreteDataImportTransfer
    {
        $productConcrete = new TestProductConcreteDataImportTransfer();
        $productConcrete->setNameEN(self::PRODUCT_CONCRETE_6_NAME_EN);
        $productConcrete->setNameDE(self::PRODUCT_CONCRETE_6_NAME_DE);
        $productConcrete->setSku(self::PRODUCT_CONCRETE_6_SKU);
        $productConcrete->setIsActive(self::PRODUCT_CONCRETE_6_IS_ACTIVE);
        $productConcrete->setAttribute($this->getAttributeArrayObject(self::PRODUCT_CONCRETE_6_ATTRIBUTES));

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_CONCRETE_6_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_CONCRETE_6_IMAGE_URL_SMALL);

        $stockDataImport = new TestStockDataImportTransfer();
        $stockDataImport->setIsNeverOutOfStock(self::PRODUCT_CONCRETE_6_STOCK_IS_NEVER_OUT_OF);
        $stockDataImport->setQuantity(self::PRODUCT_CONCRETE_6_STOCK_QUANTITY);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_CONCRETE_6_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_CONCRETE_6_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_CONCRETE_6_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_CONCRETE_6_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_CONCRETE_6_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_CONCRETE_6_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productConcrete->setStock($stockDataImport);
        $productConcrete->setImage($imageSetDataImport);
        $productConcrete->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );

        return $productConcrete;
    }

    /**
     * @return \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer
     */
    public function getProductConcreteAffiliate(): TestProductConcreteDataImportTransfer
    {
        $productConcrete = new TestProductConcreteDataImportTransfer();
        $productConcrete->setNameEN(self::PRODUCT_AFFILIATE_NAME_EN);
        $productConcrete->setNameDE(self::PRODUCT_AFFILIATE_NAME_DE);
        $productConcrete->setSku(self::PRODUCT_AFFILIATE_CONCRETE_SKU);
        $productConcrete->setIsActive(self::PRODUCT_AFFILIATE_IS_ACTIVE);
        $productConcrete->setAttribute($this->getAttributeArrayObject(self::PRODUCT_AFFILIATE_ATTRIBUTES));

        $imageSetDataImport = new TestImageSetDataImportTransfer();
        $imageSetDataImport->setUrlLarge(self::PRODUCT_AFFILIATE_IMAGE_URL_LARGE);
        $imageSetDataImport->setUrlSmall(self::PRODUCT_AFFILIATE_IMAGE_URL_SMALL);

        $stockDataImport = new TestStockDataImportTransfer();
        $stockDataImport->setIsNeverOutOfStock(self::PRODUCT_AFFILIATE_STOCK_IS_NEVER_OUT_OF);
        $stockDataImport->setQuantity(self::PRODUCT_AFFILIATE_STOCK_QUANTITY);

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::PRODUCT_AFFILIATE_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::PRODUCT_AFFILIATE_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::PRODUCT_AFFILIATE_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::PRODUCT_AFFILIATE_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::PRODUCT_AFFILIATE_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::PRODUCT_AFFILIATE_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $productConcrete->setStock($stockDataImport);
        $productConcrete->setImage($imageSetDataImport);
        $productConcrete->setPrice(
            new ArrayObject(
                [
                    $priceDataImportDefault,
                    $priceDataImportOrigin,
                ]
            )
        );

        $productConcrete->setOffer(
            new ArrayObject(
                [
                    $this->getAffiliateOffer1(),
                    $this->getAffiliateOffer2(),
                ]
            )
        );

        return $productConcrete;
    }

    /**
     * @param array $attributes
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\TestAttributeDataImportTransfer[]
     */
    private function getAttributeArrayObject(array $attributes): ArrayObject
    {
        $attributesArrayObject = new ArrayObject();
        foreach ($attributes as $name => $value) {
            $testAttributeDataImport = new TestAttributeDataImportTransfer();
            $testAttributeDataImport->setName($name);
            $testAttributeDataImport->setValue($value);
            $attributesArrayObject->append($testAttributeDataImport);
        }

        return $attributesArrayObject;
    }

    /**
     * @return \Generated\Shared\Transfer\TestOfferDataImportTransfer
     */
    private function getAffiliateOffer1(): TestOfferDataImportTransfer
    {
        $offer = new TestOfferDataImportTransfer();
        $offer->setStore(self::OFFER_MERCHANT_STORE_1);
        $offer->setMerchantReference(self::OFFER_MERCHANT_REFERENCE_1);
        $offer->setMerchantSku(self::OFFER_MERCHANT_SKU_1);
        $offer->setAffiliateData($this->getAttributeArrayObject(self::OFFER_AFFILIATE_DATA_1));

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::OFFER_MERCHANT_STORE_1_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::OFFER_MERCHANT_STORE_1_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::OFFER_MERCHANT_STORE_1_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::OFFER_MERCHANT_STORE_1_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::OFFER_MERCHANT_STORE_1_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::OFFER_MERCHANT_STORE_1_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $offer->setPrice((new ArrayObject([$priceDataImportDefault, $priceDataImportOrigin])));

        return $offer;
    }

    /**
     * @return \Generated\Shared\Transfer\TestOfferDataImportTransfer
     */
    private function getAffiliateOffer2(): TestOfferDataImportTransfer
    {
        $offer = new TestOfferDataImportTransfer();
        $offer->setStore(self::OFFER_MERCHANT_STORE_2);
        $offer->setMerchantReference(self::OFFER_MERCHANT_REFERENCE_2);
        $offer->setMerchantSku(self::OFFER_MERCHANT_SKU_2);
        $offer->setAffiliateData($this->getAttributeArrayObject(self::OFFER_AFFILIATE_DATA_2));

        $priceDataImportDefault = new TestPriceDataImportTransfer();
        $priceDataImportDefault->setCurrency(self::OFFER_MERCHANT_STORE_2_PRICE_CURRENCY);
        $priceDataImportDefault->setValueNet(self::OFFER_MERCHANT_STORE_2_PRICE_NET_DEFAULT);
        $priceDataImportDefault->setValueGross(self::OFFER_MERCHANT_STORE_2_PRICE_GROSS_DEFAULT);
        $priceDataImportDefault->setType(self::PRICE_TYPE_DEFAULT);

        $priceDataImportOrigin = new TestPriceDataImportTransfer();
        $priceDataImportOrigin->setCurrency(self::OFFER_MERCHANT_STORE_2_PRICE_CURRENCY);
        $priceDataImportOrigin->setValueNet(self::OFFER_MERCHANT_STORE_2_PRICE_NET_ORIGINAL);
        $priceDataImportOrigin->setValueGross(self::OFFER_MERCHANT_STORE_2_PRICE_GROSS_ORIGINAL);
        $priceDataImportOrigin->setType(self::PRICE_TYPE_ORIGINAL);

        $offer->setPrice((new ArrayObject([$priceDataImportDefault, $priceDataImportOrigin])));

        return $offer;
    }
}
