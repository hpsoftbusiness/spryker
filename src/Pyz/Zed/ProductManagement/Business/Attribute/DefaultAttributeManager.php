<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business\Attribute;

class DefaultAttributeManager implements DefaultAttributeManagerInterface
{
    /**
     * @var null[]
     */
    private $attributes = [
        'sellable_ae' => null,
        'sellable_at' => null,
        'sellable_au' => null,
        'sellable_ba' => null,
        'sellable_be' => null,
        'sellable_bg' => null,
        'sellable_br' => null,
        'sellable_by' => null,
        'sellable_ca' => null,
        'sellable_ch' => null,
        'sellable_co' => null,
        'sellable_cy' => null,
        'sellable_cz' => null,
        'sellable_de' => null,
        'sellable_dk' => null,
        'sellable_ee' => null,
        'sellable_es' => null,
        'sellable_fi' => null,
        'sellable_fr' => null,
        'sellable_gb' => null,
        'sellable_gr' => null,
        'sellable_hk' => null,
        'sellable_hr' => null,
        'sellable_hu' => null,
        'sellable_ie' => null,
        'sellable_im' => null,
        'sellable_in' => null,
        'sellable_it' => null,
        'sellable_lt' => null,
        'sellable_lu' => null,
        'sellable_lv' => null,
        'sellable_mc' => null,
        'sellable_md' => null,
        'sellable_me' => null,
        'sellable_mk' => null,
        'sellable_mo' => null,
        'sellable_mt' => null,
        'sellable_mx' => null,
        'sellable_my' => null,
        'sellable_nl' => null,
        'sellable_no' => null,
        'sellable_nz' => null,
        'sellable_ph' => null,
        'sellable_pl' => null,
        'sellable_pt' => null,
        'sellable_qa' => null,
        'sellable_ro' => null,
        'sellable_rs' => null,
        'sellable_se' => null,
        'sellable_si' => null,
        'sellable_sk' => null,
        'sellable_th' => null,
        'sellable_tr' => null,
        'sellable_us' => null,
        'sellable_za' => null,
        'shopping_points' => null,
        'cashback_amount' => null,
        'manufacturer' => null,
        'brand' => null,
        'length' => null,
        'width' => null,
        'height' => null,
        'weight' => null,
        'color_01' => null,
        'size_01' => null,
        'mpn' => null,
        'ean' => null,
        'gtin' => null,
        'TARIC Code' => null,
        'country_of_origin' => null,
    ];

    /**
     * @return null[]
     */
    public function getDefaultAttributes(): array
    {
        return $this->attributes;
    }
}
