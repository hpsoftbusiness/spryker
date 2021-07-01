<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Calculation\Communication\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Communication\Controller\GatewayController as SprykerGatewayController;

class GatewayController extends SprykerGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculateWithoutPluginsAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->recalculateQuote($quoteTransfer, false);
    }
}
