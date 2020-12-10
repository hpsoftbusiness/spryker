<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Country;

use Pyz\Yves\Country\Expander\AddressExpander;
use Pyz\Yves\Country\Expander\AddressExpanderInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Country\CountryClientInterface getClient()
 */
class CountryFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Yves\Country\Expander\AddressExpanderInterface
     */
    public function createAddressExpander(): AddressExpanderInterface
    {
        return new AddressExpander($this->getClient());
    }
}
