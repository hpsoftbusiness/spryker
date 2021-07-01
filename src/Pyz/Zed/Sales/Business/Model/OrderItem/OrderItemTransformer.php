<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Business\Model\OrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformer as SprykerOrderItemTransformer;

class OrderItemTransformer extends SprykerOrderItemTransformer
{
    /**
     * @var \Pyz\Zed\Sales\Dependency\Plugin\OrderItemTransformerPluginInterface[]
     */
    private $orderItemTransformerPlugins;

    /**
     * @param \Pyz\Zed\Sales\Dependency\Plugin\OrderItemTransformerPluginInterface[] $orderItemTransformerPlugins
     */
    public function __construct(array $orderItemTransformerPlugins)
    {
        $this->orderItemTransformerPlugins = $orderItemTransformerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function transformSplittableItem(ItemTransfer $itemTransfer): ItemCollectionTransfer
    {
        $transformedItemsCollection = new ItemCollectionTransfer();

        $quantity = $itemTransfer->getQuantity();
        for ($i = 1; $quantity >= $i; $i++) {
            $transformedItemTransfer = new ItemTransfer();
            $transformedItemTransfer->fromArray($itemTransfer->toArray(), true);
            $transformedItemTransfer->setQuantity(1);

            $transformedProductOptions = new ArrayObject();
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $transformedProductOptions->append($this->copyProductOptionTransfer($productOptionTransfer));
            }

            $transformedItemTransfer->setProductOptions($transformedProductOptions);
            $this->executeTransformerPlugins($itemTransfer, $transformedItemTransfer);

            $transformedItemsCollection->addItem($transformedItemTransfer);
        }

        return $transformedItemsCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $originalItem
     * @param \Generated\Shared\Transfer\ItemTransfer $transformedItem
     *
     * @return void
     */
    private function executeTransformerPlugins(ItemTransfer $originalItem, ItemTransfer $transformedItem): void
    {
        foreach ($this->orderItemTransformerPlugins as $transformerPlugin) {
            $transformerPlugin->transform($originalItem, $transformedItem);
        }
    }
}
