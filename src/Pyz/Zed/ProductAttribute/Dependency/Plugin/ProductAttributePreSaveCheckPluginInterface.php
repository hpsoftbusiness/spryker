<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Dependency\Plugin;

interface ProductAttributePreSaveCheckPluginInterface
{
    /**
     * Specification:
     * - Checks product attributes before saving them for a product.
     * - Expected $attributes parameter structure:
     *  [
     *      localeName => [
     *          attributeKey => attributeValue,
     *      ],
     *  ]
     *
     * @param array $attributes
     *
     * @throws \Pyz\Zed\ProductAttribute\Business\Exception\ProductAttributeCheckException
     *
     * @return void
     */
    public function check(array $attributes): void;
}
