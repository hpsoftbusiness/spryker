<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Calculation;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Calculation\CalculationClientInterface as SprykerCalculationClientInterface;

interface CalculationClientInterface extends SprykerCalculationClientInterface
{
    /**
     * Recalculates totals without running quote plugins
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculateWithoutPluginsAction(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
