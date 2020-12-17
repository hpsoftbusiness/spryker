<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Dependency\Plugin;

interface ProductAbstractPageAfterPublishPluginInterface
{
    /**
     * Specification:
     * - Executes after product abstract page publishing.
     *
     * @api
     *
     * @return void
     */
    public function execute(): void;
}
