<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Mapper;

use Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapper as SprykerProductPageSearchMapper;

class ProductPageSearchMapper extends SprykerProductPageSearchMapper
{
    /**
     * @param array $productAbstractLocalizedData
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer
     */
    public function mapToProductPageSearchTransfer(array $productAbstractLocalizedData)
    {
        $productPageSearchTransfer = parent::mapToProductPageSearchTransfer($productAbstractLocalizedData);
        $productPageSearchTransfer->setName(addslashes($productAbstractLocalizedData['name']));
        $productPageSearchTransfer->setIsAffiliate((bool)$productAbstractLocalizedData['SpyProductAbstract']['is_affiliate'] ?? false);
        $productPageSearchTransfer->setAffiliateData(
            $productAbstractLocalizedData['SpyProductAbstract']['affiliate_data']
                ? json_decode($productAbstractLocalizedData['SpyProductAbstract']['affiliate_data'], true)
                : null
        );
        $productPageSearchTransfer->setBrand($productAbstractLocalizedData['SpyProductAbstract']['attributes']['brand'] ?? null);

        if ($productPageSearchTransfer->getAttributes()) {
            $productPageSearchTransfer->setAttributes(
                $this->sanitizeBooleansValues($productPageSearchTransfer->getAttributes())
            );
        }

        return $productPageSearchTransfer;
    }

    /**
     * TODO: refactor how we deal with boolean values: https://spryker.atlassian.net/browse/MYW-1275
     *
     * @param array $values
     *
     * @return array
     */
    private function sanitizeBooleansValues(array $values): array
    {
        foreach ($values as $key => $value) {
            if (is_string($value) && ($value === '1' || $value === '0')) {
                $values[$key] = (bool)$value[0];
            }
        }

        return $values;
    }
}
