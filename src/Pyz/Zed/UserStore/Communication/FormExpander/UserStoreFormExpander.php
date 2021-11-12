<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication\FormExpander;

use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Pyz\Zed\UserStore\Business\UserStoreFacadeInterface getFacade()
 * @method \Pyz\Zed\UserStore\Communication\UserStoreCommunicationFactory getFactory()
 */
class UserStoreFormExpander extends AbstractType
{
    protected const FIELD_FK_STORE = 'fk_store';

    public const OPTIONS_STORE = 'OPTIONS_STORE';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addStoreField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_FK_STORE, SelectType::class, [
            'label' => 'Restrict to store',
            'choices' => $options[static::OPTIONS_STORE],
            'required' => false,
        ]);
    }
}
