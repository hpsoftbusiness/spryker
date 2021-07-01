<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Calculation\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Calculation\Zed\CalculationStub as SprykerCalculationStub;

class CalculationStub extends SprykerCalculationStub implements CalculationStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculateWithoutPluginsAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call(
            '/calculation/gateway/recalculate-without-plugins',
            $quoteTransfer
        );

        return $quoteTransfer;
    }
}
