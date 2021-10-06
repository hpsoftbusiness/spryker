<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethod;

use Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethodFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\ShipmentMethodForm as SprykerShipmentMethodForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ShipmentMethodForm
 *
 * @package Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethod
 *
 * @method \Pyz\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class ShipmentMethodForm extends SprykerShipmentMethodForm
{
    public const FIELD_DEFAULT_IN_STORES_RELATION = 'defaultInStoresRelation';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $this->addDefaultInStoresRelationForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addDefaultInStoresRelationForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_DEFAULT_IN_STORES_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
                'required' => false,
                'disabled' => $options[ShipmentMethodFormDataProvider::OPTION_DEFAULT_IN_STORE_RELATION_DISABLED],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $required = $resolver->getRequiredOptions();
        array_push($required, ShipmentMethodFormDataProvider::OPTION_DEFAULT_IN_STORE_RELATION_DISABLED);
        $resolver->setRequired($required);
    }
}
