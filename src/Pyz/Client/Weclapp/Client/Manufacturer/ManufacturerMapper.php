<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Manufacturer;

use Generated\Shared\Transfer\WeclappArticleTransfer;
use Generated\Shared\Transfer\WeclappManufacturerTransfer;

class ManufacturerMapper implements ManufacturerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappManufacturerTransfer
     */
    public function mapWeclappArticleToWeclappManufacturer(
        WeclappArticleTransfer $weclappArticleTransfer
    ): WeclappManufacturerTransfer {
        $weclappManufacturerTransfer = new WeclappManufacturerTransfer();
        $weclappManufacturerTransfer->setName($weclappArticleTransfer->getManufacturerNameOrFail());

        return $weclappManufacturerTransfer;
    }
}
