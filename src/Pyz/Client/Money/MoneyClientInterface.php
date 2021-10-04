<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Money;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Client\Money\MoneyClientInterface as SprykerMoneyClientInterface;

interface MoneyClientInterface extends SprykerMoneyClientInterface
{
    /**
     * Specification:
     * - Returns a formatted money without the currency symbol
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer): string;
}
