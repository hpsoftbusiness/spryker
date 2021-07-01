<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Calculation;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Calculation\CalculationClient as SprykerCalculationClient;

/**
 * @method \Pyz\Client\Calculation\CalculationFactory getFactory()
 */
class CalculationClient extends SprykerCalculationClient implements CalculationClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculateWithoutPluginsAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createZedStub()->recalculateWithoutPluginsAction($quoteTransfer);
    }
}
