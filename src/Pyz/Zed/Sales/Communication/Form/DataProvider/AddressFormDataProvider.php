<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Form\DataProvider;

use Pyz\Zed\Sales\Communication\Form\AddressForm;
use Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider as SprykerAddressFormDataProvider;

class AddressFormDataProvider extends SprykerAddressFormDataProvider
{
    /**
     * @param int $idOrderAddress
     *
     * @return array
     */
    public function getData($idOrderAddress): array
    {
        $parentData = parent::getData($idOrderAddress);
        $address = $this->salesQueryContainer->querySalesOrderAddressById($idOrderAddress)->findOne();

        return array_merge($parentData, [
            AddressForm::FIELD_VAT_NUMBER => $address->getVatNumber(),
            AddressForm::FIELD_ADDRESS_4 => $address->getAddress4(),
            AddressForm::FIELD_STATE => $address->getState(),
        ]);
    }
}
