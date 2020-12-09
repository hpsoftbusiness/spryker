<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Country\Plugin\CheckoutPage;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface;

/**
 * @method \Pyz\Yves\Country\CountryFactory getFactory()
 */
class CountryAddressExpanderPlugin extends AbstractPlugin implements AddressTransferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands address transfer with country data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expand(AddressTransfer $addressTransfer, ?CustomerTransfer $customerTransfer): AddressTransfer
    {
        return $this->getFactory()
            ->createAddressExpander()
            ->expandAddressWithCountry($addressTransfer);
    }
}
