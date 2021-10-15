<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\CustomsTariffNumber;

use Generated\Shared\Transfer\WeclappArticleTransfer;
use Generated\Shared\Transfer\WeclappCustomsTariffNumberTransfer;

class CustomsTariffNumberMapper implements CustomsTariffNumberMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappCustomsTariffNumberTransfer
     */
    public function mapWeclappArticleToWeclappCustomsTariffNumber(
        WeclappArticleTransfer $weclappArticleTransfer
    ): WeclappCustomsTariffNumberTransfer {
        $weclappCustomsTariffNumberTransfer = new WeclappCustomsTariffNumberTransfer();
        $weclappCustomsTariffNumberTransfer->setName($weclappArticleTransfer->getCustomsTariffNumberOrFail());

        return $weclappCustomsTariffNumberTransfer;
    }
}
