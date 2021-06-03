<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business\Attribute;

use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;

class DefaultAttributeManager implements DefaultAttributeManagerInterface
{
    /**
     * @var string[]
     */
    private $attributes = [
        'sellable_ae',
        'sellable_at',
        'sellable_au',
        'sellable_ba',
        'sellable_be',
        'sellable_bg',
        'sellable_br',
        'sellable_by',
        'sellable_ca',
        'sellable_ch',
        'sellable_co',
        'sellable_cy',
        'sellable_cz',
        'sellable_de',
        'sellable_dk',
        'sellable_ee',
        'sellable_es',
        'sellable_fi',
        'sellable_fr',
        'sellable_gb',
        'sellable_gr',
        'sellable_hk',
        'sellable_hr',
        'sellable_hu',
        'sellable_ie',
        'sellable_im',
        'sellable_in',
        'sellable_it',
        'sellable_lt',
        'sellable_lu',
        'sellable_lv',
        'sellable_mc',
        'sellable_md',
        'sellable_me',
        'sellable_mk',
        'sellable_mo',
        'sellable_mt',
        'sellable_mx',
        'sellable_my',
        'sellable_nl',
        'sellable_no',
        'sellable_nz',
        'sellable_ph',
        'sellable_pl',
        'sellable_pt',
        'sellable_qa',
        'sellable_ro',
        'sellable_rs',
        'sellable_se',
        'sellable_si',
        'sellable_sk',
        'sellable_th',
        'sellable_tr',
        'sellable_us',
        'sellable_za',
        'shopping_points',
        'cashback_amount',
        'manufacturer',
        'brand',
        'length',
        'width',
        'height',
        'weight',
        'color_01',
        'size_01',
        'mpn',
        'ean',
        'gtin',
        'taric_code',
        'country_of_origin',
        'featured_products',
    ];

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    private $spyProductAttributeKeyQuery;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $spyProductAttributeKeyQuery
     */
    public function __construct(
        SpyProductAttributeKeyQuery $spyProductAttributeKeyQuery
    ) {
        $this->spyProductAttributeKeyQuery = $spyProductAttributeKeyQuery;
    }

    /**
     * @return null[]
     */
    public function getDefaultAttributes(): array
    {
        $attributeKeyEntities = $this->spyProductAttributeKeyQuery->filterByKey_In($this->attributes)->find();

        $attributesArray = [];
        /** @var \Orm\Zed\Product\Persistence\SpyProductAttributeKey $attributeKeyEntity */
        foreach ($attributeKeyEntities->getData() as $attributeKeyEntity) {
            $attributesArray[$attributeKeyEntity->getKey()] = null;
        }

        return $attributesArray;
    }
}
