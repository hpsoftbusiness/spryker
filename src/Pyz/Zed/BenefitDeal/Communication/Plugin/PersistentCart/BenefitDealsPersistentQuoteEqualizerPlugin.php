<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Communication\Plugin\PersistentCart;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\PersistentCart\Dependency\Plugin\PersistentQuoteEqualizerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\BenefitDeal\Business\BenefitDealFacadeInterface getFacade()
 * @method \Pyz\Zed\BenefitDeal\BenefitDealConfig getConfig()
 */
class BenefitDealsPersistentQuoteEqualizerPlugin extends AbstractPlugin implements PersistentQuoteEqualizerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $persistentQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sessionQuoteTransfer
     *
     * @return void
     */
    public function equalize(
        QuoteTransfer $persistentQuoteTransfer,
        QuoteTransfer $sessionQuoteTransfer
    ): void {
        if ($persistentQuoteTransfer->getItems()->count() !== $sessionQuoteTransfer->getItems()->count()) {
            return;
        }

        $this->getFacade()->equalizeQuoteItemsBenefitDealUsageFlags($persistentQuoteTransfer, $sessionQuoteTransfer);
    }
}
