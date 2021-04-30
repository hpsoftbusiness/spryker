<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Quote;

use Spryker\Client\Quote\QuoteClient as SprykerQuoteClient;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;

class QuoteClient extends SprykerQuoteClient implements CheckoutPageToQuoteClientInterface
{
}
