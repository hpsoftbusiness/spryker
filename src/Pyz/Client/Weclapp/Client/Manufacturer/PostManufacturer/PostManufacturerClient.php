<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Manufacturer\PostManufacturer;

use Generated\Shared\Transfer\WeclappArticleTransfer;
use Pyz\Client\Weclapp\Client\Manufacturer\AbstractWeclappManufacturerClient;

class PostManufacturerClient extends AbstractWeclappManufacturerClient implements PostManufacturerClientInterface
{
    protected const REQUEST_METHOD = 'POST';
    protected const ACTION_URL = '/manufacturer';

    /**
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return void
     */
    public function postManufacturer(WeclappArticleTransfer $weclappArticleTransfer): void
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
        return $this->manufacturerMapper
            ->mapWeclappArticleToWeclappManufacturer($weclappArticleTransfer)
            ->toArray(true, true);
    }
}
