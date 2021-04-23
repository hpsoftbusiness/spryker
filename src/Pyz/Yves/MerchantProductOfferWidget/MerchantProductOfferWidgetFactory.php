<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MerchantProductOfferWidget;

use Pyz\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReader;
use SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory as SprykerMerchantProductOfferWidgetFactory;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface;

class MerchantProductOfferWidgetFactory extends SprykerMerchantProductOfferWidgetFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface
     */
    public function createProductOfferReader(): MerchantProductOfferReaderInterface
    {
        return new MerchantProductOfferReader(
            $this->getMerchantProductOfferStorageClient(),
            $this->createShopContextResolver(),
            $this->getMerchantStorageClient()
        );
    }
}
