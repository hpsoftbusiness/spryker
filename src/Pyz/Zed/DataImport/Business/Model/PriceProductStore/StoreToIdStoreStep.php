<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\PriceProductStore;

use Spryker\Zed\PriceProductOfferDataImport\Business\Step\StoreToIdStoreStep as SprykerStoreToIdStoreStep;

class StoreToIdStoreStep extends SprykerStoreToIdStoreStep
{
    protected const STORE = 'product_abstract_store.store_name';
}
