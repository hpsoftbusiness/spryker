<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProfile\Persistence;

use Pyz\Zed\MerchantProfile\Persistence\Propel\Mapper\MerchantProfileAddressMapper;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfilePersistenceFactory as SprykerMerchantProfilePersistenceFactory;
use Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper\MerchantProfileAddressMapperInterface;

class MerchantProfilePersistenceFactory extends SprykerMerchantProfilePersistenceFactory
{
    /**
     * @return \Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper\MerchantProfileAddressMapperInterface
     */
    public function createMerchantProfileAddressMapper(): MerchantProfileAddressMapperInterface
    {
        return new MerchantProfileAddressMapper();
    }
}
