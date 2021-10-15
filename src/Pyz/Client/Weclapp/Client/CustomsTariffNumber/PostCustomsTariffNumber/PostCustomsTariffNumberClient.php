<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\CustomsTariffNumber\PostCustomsTariffNumber;

use Generated\Shared\Transfer\WeclappArticleTransfer;
use Pyz\Client\Weclapp\Client\CustomsTariffNumber\AbstractWeclappCustomsTariffNumberClient;

class PostCustomsTariffNumberClient extends AbstractWeclappCustomsTariffNumberClient implements PostCustomsTariffNumberClientInterface
{
    protected const REQUEST_METHOD = 'POST';
    protected const ACTION_URL = '/customsTariffNumber';

    /**
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return void
     */
    public function postCustomsTariffNumber(WeclappArticleTransfer $weclappArticleTransfer): void
    {
        $this->callWeclapp(
            static::REQUEST_METHOD,
            static::ACTION_URL,
            $this->getRequestBody($weclappArticleTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return array
     */
    protected function getRequestBody(WeclappArticleTransfer $weclappArticleTransfer): array
    {
        return $this->customsTariffNumberMapper
            ->mapWeclappArticleToWeclappCustomsTariffNumber($weclappArticleTransfer)
            ->toArray(true, true);
    }
}
