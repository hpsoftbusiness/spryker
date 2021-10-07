<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethod;

use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\ViewShipmentMethodForm as SprykerViewShipmentMethodForm;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ViewShipmentMethodForm
 *
 * @package Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethod
 *
 * @method \Pyz\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class ViewShipmentMethodForm extends SprykerViewShipmentMethodForm
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
                'disabled' => true,
            ]
        );

        return $this;
    }
}
