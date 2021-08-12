<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\DataImport;

class DataImportConstants
{
    /**
     * Specification:
     * - cluster name for using correct import folder.
     *
     * @api
     */
    public const SPRYKER_CLUSTER = 'SPRYKER_CLUSTER';

    /**
     * Specification:
     * - bool value.
     * - checking if we need Zed/DataImport/Business/CombinedProduct/ProductAbstractStore/ValidationStoreImportStep
     *
     * @api
     */
    public const NEED_STORE_RELATION_VALIDATION = 'NEED_STORE_RELATION_VALIDATION';
}
