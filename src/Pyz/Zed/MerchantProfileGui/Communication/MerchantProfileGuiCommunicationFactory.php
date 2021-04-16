<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProfileGui\Communication;

use Pyz\Zed\MerchantProfileGui\Communication\Form\MerchantProfileFormType;
use Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory as SprykerMerchantProfileGuiCommunicationFactory;
use Symfony\Component\Form\FormTypeInterface;

class MerchantProfileGuiCommunicationFactory extends SprykerMerchantProfileGuiCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createMerchantProfileForm(): FormTypeInterface
    {
        return new MerchantProfileFormType();
    }
}
