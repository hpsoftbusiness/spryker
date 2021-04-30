<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ErrorStep as SprykerShopErrorStep;
use Symfony\Component\HttpFoundation\Request;

class ErrorStep extends SprykerShopErrorStep
{
    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    private $quoteClient;

    /**
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct($stepRoute, $escapeRoute, QuoteClientInterface $quoteClient)
    {
        parent::__construct($stepRoute, $escapeRoute);

        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setOrderReference(null)
            ->getPayment()
            ->setPaymentSelection(null);

        $this->quoteClient->clearQuote();

        return $quoteTransfer;
    }
}
