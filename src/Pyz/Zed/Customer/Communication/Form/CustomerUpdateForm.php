<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Form;

use Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm as SprykerCustomerUpdateForm;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerUpdateForm extends SprykerCustomerUpdateForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove(self::FIELD_SEND_PASSWORD_TOKEN);
    }
}
