<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui;

use Pyz\Zed\MerchantProfileGui\Communication\Form\MerchantProfileFormType;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\MerchantProfileFormExpanderPlugin as SprykerMerchantProfileFormExpanderPlugin;
use Symfony\Component\Form\FormBuilderInterface;

class MerchantProfileFormExpanderPlugin extends SprykerMerchantProfileFormExpanderPlugin
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantProfileFieldSubform(FormBuilderInterface $builder)
    {
        $options = $this->getMerchantProfileFormOptions($builder);
        $builder->add(
            static::FIELD_MERCHANT_PROFILE,
            MerchantProfileFormType::class,
            $options
        );

        return $this;
    }
}
