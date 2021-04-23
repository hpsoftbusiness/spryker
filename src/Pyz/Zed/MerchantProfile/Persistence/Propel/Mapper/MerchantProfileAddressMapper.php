<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProfile\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;
use Spryker\Zed\MerchantProfile\Persistence\Propel\Mapper\MerchantProfileAddressMapper as SprykerMerchantProfileAddressMapper;

class MerchantProfileAddressMapper extends SprykerMerchantProfileAddressMapper
{
    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress $merchantProfileAddressEntity
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function mapMerchantProfileAddressEntityToMerchantProfileAddressTransfer(
        SpyMerchantProfileAddress $merchantProfileAddressEntity,
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer
    ): MerchantProfileAddressTransfer {
        $merchantProfileAddressTransfer->fromArray(
            $merchantProfileAddressEntity->toArray(),
            true
        );

        if ($merchantProfileAddressEntity->getSpyCountry() !== null) {
            $merchantProfileAddressTransfer->setCountryName($merchantProfileAddressEntity->getSpyCountry()->getName());
            $merchantProfileAddressTransfer->setIso2Code($merchantProfileAddressEntity->getSpyCountry()->getIso2Code());
        }

        return $merchantProfileAddressTransfer;
    }
}
