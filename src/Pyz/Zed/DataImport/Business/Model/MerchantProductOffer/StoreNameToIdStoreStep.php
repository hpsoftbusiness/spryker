<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer;

use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\StoreNameToIdStoreStep as SprykerStoreNameToIdStoreStep;

class StoreNameToIdStoreStep extends SprykerStoreNameToIdStoreStep
{
    protected const STORE_NAME = 'product_abstract_store.store_name';
}
