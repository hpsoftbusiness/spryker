<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountCollectorPluginInterface;

class InternalDiscountCollector implements InternalDiscountCollectorInterface
{
    /**
     * @var \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountCollectorPluginInterface[]
     */
    private $internalDiscountCollectorPlugins;

    /**
     * @param \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountCollectorPluginInterface[] $internalDiscountCollectorPlugins
     */
    public function __construct(array $internalDiscountCollectorPlugins)
    {
        $this->internalDiscountCollectorPlugins = $internalDiscountCollectorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer): array
    {
        $applicableCollectorPlugin = $this->findApplicableCollectorPlugin($discountTransfer->getCollectorPlugin());
        if (!$applicableCollectorPlugin) {
            return [];
        }

        return $applicableCollectorPlugin->collect($quoteTransfer);
    }

    /**
     * @param string $collectorPluginName
     *
     * @return \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountCollectorPluginInterface|null
     */
    private function findApplicableCollectorPlugin(string $collectorPluginName): ?InternalDiscountCollectorPluginInterface
    {
        foreach ($this->internalDiscountCollectorPlugins as $collectorPlugin) {
            if ($collectorPlugin->getName() === $collectorPluginName) {
                return $collectorPlugin;
            }
        }

        return null;
    }
}
