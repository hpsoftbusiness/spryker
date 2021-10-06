<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShipmentGui\Communication\Form;

use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentMethodFormDataProvider as SprykerShipmentMethodFormDataProvider;

class ShipmentMethodFormDataProvider extends SprykerShipmentMethodFormDataProvider
{
    public const OPTION_DEFAULT_IN_STORE_RELATION_DISABLED = 'option_default_in_stores_relation_disabled';

    /**
     * @param bool $isDeliveryKeyDisabled
     *
     * @return array
     */
    public function getOptions(bool $isDeliveryKeyDisabled = false): array
    {
        $options = parent::getOptions($isDeliveryKeyDisabled);
        $options[static::OPTION_DEFAULT_IN_STORE_RELATION_DISABLED] = false;

        return $options;
    }
}
