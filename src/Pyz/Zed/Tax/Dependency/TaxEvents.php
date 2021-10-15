<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Tax\Dependency;

use Spryker\Zed\Tax\Dependency\TaxEvents as SprykerTaxEvents;

interface TaxEvents extends SprykerTaxEvents
{
    /**
     * Specification:
     * - This event will be used for tax_rate entity create.
     *
     * @api
     */
    public const ENTITY_SPY_TAX_RATE_CREATE = 'Entity.spy_tax_rate.create';
}
