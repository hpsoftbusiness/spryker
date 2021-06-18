<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Traits;

use ArrayObject;
use Generated\Shared\Transfer\AdyenApiAmountTransfer;
use Generated\Shared\Transfer\AdyenApiSplitTransfer;
use Pyz\Shared\Adyen\AdyenConfig as SharedAdyenConfig;

trait AdyenApiSplitsTrait
{
    /**
     * @var \Pyz\Zed\Adyen\AdyenConfig
     */
    protected $config;

    /**
     * @param string $splitMarketplaceReference
     * @param string $splitCommissionReference
     * @param \Generated\Shared\Transfer\AdyenApiAmountTransfer $adyenApiAmountTransfer
     *
     * @return \ArrayObject
     */
    protected function createAdyenApiSplits(
        string $splitMarketplaceReference,
        string $splitCommissionReference,
        AdyenApiAmountTransfer $adyenApiAmountTransfer
    ): ArrayObject {
        $commissionAmount = (int)round($adyenApiAmountTransfer->getValue() * $this->config->getSplitAccountCommissionInterest());
        $marketplaceAmount = (int)$adyenApiAmountTransfer->getValue() - $commissionAmount;

        $marketplaceSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($marketplaceAmount)
            )
            ->setType(SharedAdyenConfig::SPLIT_TYPE_MARKETPLACE)
            ->setAccount($this->config->getSplitAccount())
            ->setReference($splitMarketplaceReference);

        $commissionSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($commissionAmount)
            )
            ->setType(SharedAdyenConfig::SPLIT_TYPE_COMMISSION)
            ->setReference($splitCommissionReference);

        return new ArrayObject([
            $marketplaceSplitTransfer,
            $commissionSplitTransfer,
        ]);
    }
}
