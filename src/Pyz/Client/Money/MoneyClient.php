<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Money;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Client\Money\MoneyClient as SprykerMoneyClient;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;

/**
 * @method \Spryker\Client\Money\MoneyFactory getFactory()
 */
class MoneyClient extends SprykerMoneyClient implements MoneyClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer): string
    {
        return $this
            ->getFactory()
            ->createMoneyFormatter()
            ->format($moneyTransfer, MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL);
    }
}
