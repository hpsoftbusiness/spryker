<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Attribute;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeReaderInterface as SprykerAttributeReaderInterface;

interface AttributeReaderInterface extends SprykerAttributeReaderInterface
{
    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function getAttributeByKey(string $key): ?ProductManagementAttributeTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductSuperAttributeCollection(): array;
}
