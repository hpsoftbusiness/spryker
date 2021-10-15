<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Manufacturer\PostManufacturer;

use Generated\Shared\Transfer\WeclappArticleTransfer;

interface PostManufacturerClientInterface
{
    /**
     * Post manufacturer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return void
     */
    public function postManufacturer(WeclappArticleTransfer $weclappArticleTransfer): void;
}
