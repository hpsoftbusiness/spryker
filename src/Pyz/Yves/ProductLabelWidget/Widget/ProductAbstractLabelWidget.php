<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductLabelWidget\Widget;

use SprykerShop\Yves\ProductLabelWidget\Widget\ProductAbstractLabelWidget as SprykerProductAbstractLabelWidget;

/**
 * @method \Pyz\Yves\ProductLabelWidget\ProductLabelWidgetFactory getFactory()
 */
class ProductAbstractLabelWidget extends SprykerProductAbstractLabelWidget
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    protected function getProductLabelDictionaryItems(int $idProductAbstract): array
    {
        $firstLabel = $this->getFactory()
            ->getProductLabelStorageClient()
            ->getFirstLabelByIdProductAbstract($idProductAbstract, $this->getLocale(), APPLICATION_STORE);

        return [$firstLabel];
    }
}
