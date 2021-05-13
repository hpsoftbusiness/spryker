<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker;

use Generated\Shared\Transfer\QuoteTransfer;

class BreadcrumbStatusChecker implements BreadcrumbStatusCheckerInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface[]
     */
    private $previousStepsPostConditionCheckers;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface[] $previousStepsPostConditionCheckers
     */
    public function __construct(array $previousStepsPostConditionCheckers)
    {
        $this->previousStepsPostConditionCheckers = $previousStepsPostConditionCheckers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isEnabled(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($this->previousStepsPostConditionCheckers as $postConditionChecker) {
            if (!$postConditionChecker->check($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
