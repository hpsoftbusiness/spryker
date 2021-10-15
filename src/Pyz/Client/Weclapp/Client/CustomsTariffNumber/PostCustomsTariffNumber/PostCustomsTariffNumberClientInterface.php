<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\CustomsTariffNumber\PostCustomsTariffNumber;

use Generated\Shared\Transfer\WeclappArticleTransfer;

interface PostCustomsTariffNumberClientInterface
{
    /**
     * Post customs tariff number
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return void
     */
    public function postCustomsTariffNumber(WeclappArticleTransfer $weclappArticleTransfer): void;
}
